<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for booking chat room
// Access: the user who made the booking OR the bodyguard assigned to it
Broadcast::channel('booking.{bookingId}', function ($user, int $bookingId) {
    $booking = Booking::find($bookingId);
    if (!$booking) return false;

    $isUser      = $user->id === $booking->user_id;
    $isBodyguard = $user->bodyguard && $user->bodyguard->id === $booking->bodyguard_id;

    return $isUser || $isBodyguard;
});
