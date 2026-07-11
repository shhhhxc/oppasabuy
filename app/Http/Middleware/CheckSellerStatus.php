<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSellerStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Kung 'seller' na ang role niya sa users table, huwag mo nang harangan, papasukin mo na agad!
        if ($user && $user->role === 'seller')  {
            return $next($request);
        }

        $verification = $user->sellerVerification; 

        if (!$verification) {
            return redirect()->route('vendor.register'); 
        }

        if ($verification->status === 'pending') {
            return redirect()->route('buyer.dashboard')
                ->with('show_pending_modal', true)
                ->with('warning', 'Your seller application is currently pending verification.');
        }

        return $next($request);
    }
}