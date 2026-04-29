<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the main dashboard view based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'bodyguard') {
            return $this->bodyguardDashboard($user);
        }

        // Default User Dashboard (My Bookings overview)
        return $this->userDashboard($user);
    }

    /**
     * User's dashboard: overview of active/pending missions.
     */
    private function userDashboard($user)
    {
        $activeBookings = $user->bookings()
            ->whereIn('status', ['paid', 'active'])
            ->with('bodyguard.user')
            ->latest()
            ->get();

        $pendingBookings = $user->bookings()
            ->where('status', 'pending')
            ->with('bodyguard.user')
            ->latest()
            ->get();

        return view('dashboard', compact('activeBookings', 'pendingBookings'));
    }

    /**
     * Bodyguard's dashboard: their assigned missions tracker.
     */
    private function bodyguardDashboard($user)
    {
        $bodyguard = $user->bodyguard;

        if (!$bodyguard) {
            return view('dashboard'); // Fallback if no profile
        }

        $activeMissions = $bodyguard->bookings()
            ->whereIn('status', ['paid', 'active'])
            ->with('user')
            ->latest()
            ->get();

        $upcomingMissions = $bodyguard->bookings()
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        $completedMissions = $bodyguard->bookings()
            ->where('status', 'completed')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('bodyguards.dashboard', compact(
            'bodyguard',
            'activeMissions',
            'upcomingMissions',
            'completedMissions'
        ));
    }
}
