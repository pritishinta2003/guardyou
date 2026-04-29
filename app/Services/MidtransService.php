<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MidtransService
{
    protected string $serverKey;
    protected string $baseUrl;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey   = config('midtrans.server_key');
        $this->baseUrl     = config('midtrans.api_url');
        $this->isProduction = config('midtrans.is_production');
    }

    /**
     * Create a Snap transaction and return the redirect/payment URL.
     */
    public function createSnapTransaction(Booking $booking): array
    {
        $booking->load(['user', 'bodyguard.user']);

        $orderId = 'GY-' . $booking->id . '-' . Str::random(8) . '-' . time();

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email'      => $booking->user->email,
                'phone'      => $booking->user->phone_number ?? '08000000000',
            ],
            'item_details' => [
                [
                    'id'       => 'bodyguard-' . $booking->bodyguard_id,
                    'price'    => (int) $booking->bodyguard->daily_rate,
                    'quantity' => (int) $booking->start_date->diffInDays($booking->end_date) ?: 1,
                    'name'     => 'Bodyguard: ' . ($booking->bodyguard->user->name ?? 'Agent'),
                ],
            ],
            'callbacks' => [
                'finish' => route('bookings.show', $booking),
            ],
        ];

        $response = Http::withBasicAuth($this->serverKey, '')
            ->post(config('midtrans.base_url') . '/snap/v1/transactions', $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap failed', ['booking' => $booking->id, 'response' => $response->body()]);
            throw new \Exception('Failed to create Midtrans transaction: ' . $response->body());
        }

        $data = $response->json();

        // Store the payment URL in booking
        $booking->update([
            'payment_url' => $data['redirect_url'] ?? null,
        ]);

        return $data;
    }

    /**
     * Verify webhook signature from Midtrans notification.
     */
    public function verifySignature(array $notification): bool
    {
        $orderId       = $notification['order_id'];
        $statusCode    = $notification['status_code'];
        $grossAmount   = $notification['gross_amount'];
        $serverKey     = $this->serverKey;

        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($signatureKey, $notification['signature_key'] ?? '');
    }

    /**
     * Map Midtrans transaction_status to our Booking status.
     */
    public function resolveBookingStatus(array $notification): string
    {
        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus       = $notification['fraud_status'] ?? '';

        return match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $transactionStatus === 'settlement'                            => 'paid',
            $transactionStatus === 'pending'                               => 'pending',
            in_array($transactionStatus, ['cancel', 'expire', 'deny'])    => 'cancelled',
            default                                                        => 'pending',
        };
    }
}
