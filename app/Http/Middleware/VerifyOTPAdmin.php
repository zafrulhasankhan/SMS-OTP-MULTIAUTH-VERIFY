<?php

namespace App\Http\Middleware;

use App\Models\VerificationCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyOTPAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd(Auth::user()->id);
        $user_id = Auth::user()->id;
        $lastVerifiedData = VerificationCode::where('admin_id', $user_id)
            ->where('verified', 'yes')
            ->latest()
            ->first();
        // Check if the user has a verified OTP
        // dd($lastVerifiedData);
        if ($lastVerifiedData) {
            // Proceed to the next middleware or route
            return $next($request);
        }

        return redirect()->route('otp_admin_verify');
        // return $next($request);
    }
}
