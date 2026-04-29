<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(protected MidtransService $midtrans) {}

    /**
     * Handle Midtrans payment notification webhook.
     * Endpoint: POST /webhook/midtrans (csrf-exempt, verified via signature).
     */
    public function handle(Request $request)
    {
        $notification = $request->all();

        Log::info('Midtrans Webhook Received', $notification);

        // 1. Verify Midtrans signature
        if (!$this->midtrans->verifySignature($notification)) {
            Log::warning('Midtrans Webhook: Invalid signature', $notification);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 2. Extract booking ID from order_id (format: GY-{booking_id}-{timestamp})
        $orderId = $notification['order_id'] ?? '';
        $parts   = explode('-', $orderId);

        // Format: GY-{booking_id}-{random}-{timestamp}
        if (count($parts) < 4 || $parts[0] !== 'GY') {
            return response()->json(['message' => 'Invalid order format'], 422);
        }

        $bookingId = (int) $parts[1];

        // 3. Resolve new status sebelum masuk transaction
        $newStatus = $this->midtrans->resolveBookingStatus($notification);

        $result = DB::transaction(function () use ($bookingId, $newStatus, $orderId) {
            // Pessimistic lock: cegah dua webhook duplikat diproses bersamaan
            $booking = Booking::lockForUpdate()->find($bookingId);

            if (!$booking) {
                Log::error('Midtrans Webhook: Booking not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Booking not found'], 404);
            }

            // Jangan downgrade status yang sudah final
            $statusOrder = ['pending' => 1, 'paid' => 2, 'active' => 3, 'completed' => 4, 'cancelled' => 0];
            $currentRank = $statusOrder[$booking->status] ?? 0;
            $newRank     = $statusOrder[$newStatus] ?? 0;

            if ($newRank <= $currentRank && $booking->status !== 'cancelled') {
                Log::info("Midtrans Webhook: Ignored — booking #{$bookingId} already at '{$booking->status}', incoming '{$newStatus}'");
                return response()->json(['message' => 'Ignored, no downgrade needed'], 200);
            }

            $booking->update(['status' => $newStatus]);
            Log::info("Midtrans Webhook: Booking #{$bookingId} status updated to {$newStatus}");

            return response()->json(['message' => 'Notification processed', 'status' => $newStatus]);
        });

        return $result;
    }
}
