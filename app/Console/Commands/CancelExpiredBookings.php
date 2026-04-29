<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CancelExpiredBookings extends Command
{
    protected $signature   = 'bookings:cancel-expired';
    protected $description = 'Cancel pending bookings that have not been paid within 2 hours';

    public function handle(): int
    {
        $count = Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(2))
            ->update(['status' => 'cancelled']);

        $this->info("Cancelled {$count} expired pending booking(s).");

        return self::SUCCESS;
    }
}
