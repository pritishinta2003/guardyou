<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Booking;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show the chat room for a booking.
     * Accessible by the user OR the bodyguard assigned to this booking.
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();
        $this->authorizeAccess($user, $booking);

        $booking->load(['user', 'bodyguard.user']);
        $messages = $booking->messages()->with('sender')->oldest()->get();

        return view('chat.show', compact('booking', 'messages'));
    }

    /**
     * Store a new message and broadcast it in real-time.
     */
    public function store(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $this->authorizeAccess($user, $booking);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = Message::create([
            'booking_id' => $booking->id,
            'sender_id'  => $user->id,
            'message'    => $validated['message'],
        ]);

        // Broadcast to channel — picked up by Echo client (graceful fallback if Reverb offline)
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            // Reverb not available — message still saved, polling will deliver it
        }

        // Return JSON for AJAX-based form submission
        return response()->json([
            'id'         => $message->id,
            'sender_id'  => $message->sender_id,
            'sender'     => ['id' => $user->id, 'name' => $user->name],
            'message'    => $message->message,
            'created_at' => $message->created_at->toIso8601String(),
        ], 201);
    }

    /**
     * Fetch messages newer than a given ID (for polling fallback).
     */
    public function messages(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $this->authorizeAccess($user, $booking);

        $after = $request->query('after', 0);

        $messages = $booking->messages()
            ->with('sender')
            ->where('id', '>', $after)
            ->oldest()
            ->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'sender_id'  => $m->sender_id,
                'sender'     => ['id' => $m->sender->id, 'name' => $m->sender->name],
                'message'    => $m->message,
                'created_at' => $m->created_at->toIso8601String(),
            ]);

        return response()->json($messages);
    }

    /**
     * Authorize: only booking user or the bodyguard can access chat.
     */
    private function authorizeAccess($user, Booking $booking): void
    {
        $isUser       = $user->id === $booking->user_id;
        $isBodyguard  = $user->bodyguard && $user->bodyguard->id === $booking->bodyguard_id;
        $isAdmin      = $user->role === 'admin';

        abort_if(!$isUser && !$isBodyguard && !$isAdmin, 403, 'Access denied.');
        abort_if(!in_array($booking->status, ['pending', 'paid', 'active']), 403, 'Chat only available for active bookings.');
    }
}
