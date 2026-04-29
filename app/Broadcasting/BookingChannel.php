<?php

namespace App\Broadcasting;

use App\Models\Booking;
use App\Models\User;

class BookingChannel
{
    /**
     * Authorize access to this private channel.
     * Only the user who made the booking OR the bodyguard assigned to it may connect.
     */
    public function join(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id
            || ($user->bodyguard && $user->bodyguard->id === $booking->bodyguard_id);
    }
}
