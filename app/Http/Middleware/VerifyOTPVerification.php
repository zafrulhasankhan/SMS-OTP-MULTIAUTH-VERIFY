<?php

namespace App\Http\Middleware;

use App\Models\VerificationCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyOTPVerification
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
        // dd("check");
        $user_id = Auth::user()->id;
        $lastVerifiedData = VerificationCode::where('user_id', $user_id)
            ->where('verified', 'yes')
            ->latest()
            ->first();
        // Check if the user has a verified OTP
        if ($lastVerifiedData) {
            // Proceed to the next middleware or route
            return $next($request);
        }

        // Redirect the user to the OTP verification page or perform other actions
        return redirect()->route('otp_user_verify');
        // return $next($request);
    }
}
