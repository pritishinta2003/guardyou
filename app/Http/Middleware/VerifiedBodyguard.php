<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedBodyguard
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role === 'bodyguard') {
            if (!$request->user()->bodyguard || !$request->user()->bodyguard->is_verified) {
                abort(403, 'Profil Bodyguard Anda belum diverifikasi oleh Admin.');
            }
        }
        
        return $next($request);
    }
}
