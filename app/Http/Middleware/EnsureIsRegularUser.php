<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsRegularUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'bodyguard') {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai bodyguard.');
        }

        if ($user->role === 'admin') {
            abort(403, 'Admin tidak dapat mendaftar sebagai bodyguard.');
        }

        return $next($request);
    }
}
