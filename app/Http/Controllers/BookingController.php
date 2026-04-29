<?php

namespace App\Http\Controllers;

use App\Models\Bodyguard;
use App\Models\Booking;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct(protected MidtransService $midtrans) {}

    /**
     * Booking form — User picks dates.
     */
    public function create(Bodyguard $bodyguard)
    {
        abort_if(!$bodyguard->is_verified, 404);

        $bodyguard->load('user');
        return view('bookings.create', compact('bodyguard'));
    }

    /**
     * Store booking & redirect to Midtrans payment.
     */
  public function store(Request $request, Bodyguard $bodyguard)
{
    abort_if(!$bodyguard->is_verified, 404);

    $validated = $request->validate([
        'start_date' => ['required', 'date', 'after_or_equal:today'],
        'end_date'   => ['required', 'date', 'after:start_date'],
        'alamat'     => ['nullable', 'string', 'min:10', 'max:1000'],
    ]);

    $startDate  = Carbon::parse($validated['start_date'])->startOfDay();
    $endDate    = Carbon::parse($validated['end_date'])->startOfDay();
    $days       = max($startDate->diffInDays($endDate), 1);
    $alamat     = $validated['alamat'] ?? null;
    $totalPrice = $bodyguard->daily_rate * $days;

    try {
        $booking = DB::transaction(function () use ($bodyguard, $startDate, $endDate, $totalPrice, $alamat) {
            $hasConflict = Booking::lockForUpdate()
                ->where('bodyguard_id', $bodyguard->id)
                ->whereIn('status', ['paid', 'active'])
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q2) use ($startDate, $endDate) {
                          $q2->where('start_date', '<=', $startDate)
                             ->where('end_date', '>=', $endDate);
                      });
                })
                ->exists();

            if ($hasConflict) {
                throw new \RuntimeException('Bodyguard tidak tersedia pada tanggal yang dipilih.');
            }

            return Booking::create([
                'user_id'      => Auth::id(),
                'bodyguard_id' => $bodyguard->id,
                'start_date'   => $startDate,
                'end_date'     => $endDate,
                'alamat'       => $alamat,
                'total_price'  => $totalPrice,
                'status'       => 'pending',
            ]);
        });
    } catch (\RuntimeException $e) {
        return back()->withErrors([
            'start_date' => $e->getMessage()
        ])->withInput();
    }

    try {
        $snapData = $this->midtrans->createSnapTransaction($booking);

        return redirect()->away($snapData['redirect_url']);
    } catch (\Exception $e) {
        return redirect()->route('bookings.show', $booking)
            ->with(
                'Perhatian',
                'Payment gateway tidak tersedia.' . $e->getMessage()
            );
    }
}
    /**
     * Booking detail page.
     * Accessible by: booking owner (user), assigned bodyguard, or admin.
     */
    public function show(Booking $booking)
    {
        $user        = Auth::user();
        $isOwner     = $booking->user_id === $user->id;
        $isBodyguard = $user->bodyguard?->id === $booking->bodyguard_id;
        $isAdmin     = $user->role === 'admin';

        abort_if(!$isOwner && !$isBodyguard && !$isAdmin, 403, 'Unauthorized access.');

        $booking->load(['bodyguard.user', 'user']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Bodyguard updates booking status: paid → active, active → completed.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $user        = Auth::user();
        $isBodyguard = $user->bodyguard?->id === $booking->bodyguard_id;

        abort_if(!$isBodyguard, 403, 'Only the assigned bodyguard can update booking status.');

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'completed'])],
        ]);

        $allowedTransitions = [
            'paid'   => 'active',
            'active' => 'completed',
        ];

        $expectedNew = $allowedTransitions[$booking->status] ?? null;

        if ($expectedNew !== $validated['status']) {
            return back()->withErrors(['status' => 'Transisi status tidak valid untuk kondisi saat ini.']);
        }

        $booking->update(['status' => $validated['status']]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Status booking berhasil diperbarui menjadi ' . ucfirst($validated['status']) . '.');
    }

    /**
     * User's list of all bookings.
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('bodyguard.user')
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Retry payment for a pending booking.
     */
    public function pay(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if($booking->status !== 'pending', 400);

        $booking->load(['bodyguard.user', 'user']);

        try {
            $snapData = $this->midtrans->createSnapTransaction($booking);
            return redirect()->away($snapData['redirect_url']);
        } catch (\Exception $e) {
            return back()->with('error', 'Could not initiate payment: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a pending booking.
     */
    public function cancel(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if(!in_array($booking->status, ['pending']), 400);

        $booking->update(['status' => 'cancelled']);
        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }
}
