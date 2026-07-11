<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure user is logged in before checking roles
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 1. ADMIN BYPASS: This must be first.
        // If the user role is 'admin', let them through immediately.
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 2. Role check for regular users
        if ($user->role !== 'seller') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // 3. Check actual Verification Table status
        $verification = \App\Models\SellerVerification::where('user_id', $user->id)->first();

        // If not admin and not approved, redirect to dashboard
        if (!$verification || $verification->status !== 'approved') {
            return redirect()->route('seller.dashboard')
                ->with('warning', 'Access restricted until account approval.');
        }

        return $next($request);
    }
}