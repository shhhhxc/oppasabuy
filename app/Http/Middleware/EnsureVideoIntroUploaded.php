<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVideoIntroUploaded
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure the user is actually logged in before checking roles
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // 1. Check if the user is an admin.
        // Admins bypass the video intro requirement to ensure full platform access.
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 2. Check actual Verification Table for the video path
        // Only enforce this for 'seller' roles or non-admins
        $verification = \App\Models\SellerVerification::where('user_id', $user->id)->first();

        if (!$verification || !$verification->video_intro_path) {
            // This is the line causing your redirect to /seller/dashboard
            return redirect()->route('seller.dashboard')
                ->with('error', 'Please upload your store introduction video to access this feature.');
        }

        return $next($request);
    }
}