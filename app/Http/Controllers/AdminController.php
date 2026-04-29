<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Bodyguard;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin Dashboard: statistics and overview.
     */
    public function dashboard()
    {
        $stats = [
            'total_users'     => User::where('role', 'user')->count(),
            'total_bodyguards' => Bodyguard::count(),
            'pending_verifs'  => Bodyguard::where('is_verified', false)->count(),
            'total_bookings'   => Booking::count(),
            'total_revenue'    => Booking::whereIn('status', ['paid', 'active', 'completed'])->sum('total_price'),
        ];

        $recent_bookings = Booking::with(['user', 'bodyguard.user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }

    /**
     * List and manage all bodyguards.
     */
    public function bodyguards()
    {
        $bodyguards = Bodyguard::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.bodyguards.index', compact('bodyguards'));
    }

    /**
     * Toggle bodyguard verification status.
     */
    public function verify(Bodyguard $bodyguard)
    {
        $bodyguard->update([
            'is_verified' => !$bodyguard->is_verified
        ]);

        $status = $bodyguard->is_verified ? 'verified' : 'unverified';
        
        return back()->with('success', "Guard {$bodyguard->user->name} status has {$status}.");
    }

    /**
     * List all bookings on the platform.
     */
    public function bookings()
    {
        $bookings = Booking::with(['user', 'bodyguard.user'])
            ->latest()
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * List all users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show edit form for a user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update a user's data.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'role'         => ['required', 'in:user,bodyguard,admin'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data user {$user->name} telah dihapus.");
    }

    /**
     * Delete a user.
     */
    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Cannot delete your own account.');

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User sudah dihapus.');
    }

    /**
     * Show edit form for a bodyguard.
     */
    public function editBodyguard(Bodyguard $bodyguard)
    {
        $bodyguard->load('user');
        return view('admin.bodyguards.edit', compact('bodyguard'));
    }

    /**
     * Update a bodyguard's data.
     */
    public function updateBodyguard(Request $request, Bodyguard $bodyguard)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'height'           => ['required', 'integer', 'min:100', 'max:250'],
            'weight'           => ['required', 'integer', 'min:40', 'max:200'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'daily_rate'       => ['required', 'numeric', 'min:10000'],
            'ktp_number'       => ['nullable', 'string', 'max:20'],
            'is_verified'      => ['boolean'],
        ]);

        $bodyguard->user->update(['name' => $validated['name']]);

        $bodyguard->update([
            'height'           => $validated['height'],
            'weight'           => $validated['weight'],
            'experience_years' => $validated['experience_years'],
            'daily_rate'       => $validated['daily_rate'],
            'ktp_number'       => $validated['ktp_number'] ?? $bodyguard->ktp_number,
            'is_verified'      => $request->boolean('is_verified'),
        ]);

        return redirect()->route('admin.bodyguards.index')
            ->with('success', "Data guard {$bodyguard->user->name} telah diperbarui.");
    }

    public function destroy(Bodyguard $bodyguard)
    {
        $user = $bodyguard->user;

        $bodyguard->delete();

        if ($user) {
            $user->role = 'user';
            $user->save();
        }

        return redirect()->route('admin.bodyguards.index')
            ->with('success', 'Bodyguard berhasil dihapus dan akun dikembalikan menjadi user.');
    }
}
