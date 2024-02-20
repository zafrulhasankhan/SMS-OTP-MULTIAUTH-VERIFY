<?php

namespace App\Http\Controllers\Otp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\VerificationCode;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminOtpController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function otp_verify_form()
    {
        return view("auth.admin.otp_login_admin");
    }
    public function otp_send()
    {

        $user = Admin::where('mobile_no', Auth::user()->mobile_no)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('admin_id', $user->id)->latest()->first();

        $now = Carbon::now();

        // if($verificationCode && $now->isBefore($verificationCode->expire_at)){
        //     return $verificationCode;
        // }


        $otp = rand(123456, 999999);

        VerificationCode::create([
            'admin_id' => $user->id,
            'otp' => $otp,
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);




        $to = Auth::user()->mobile_no;
        // dd(Auth::user()->mobile_no);
        $token = "";
        $message = "Your OTP code is: $otp";
        //  dd(Auth::user()->mobile_no);

        $url = "http://api.greenweb.com.bd/api.php?json";


        $data = array(
            'to' => "$to",
            'message' => "$message",
            'token' => "$token"
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);

        return redirect()->route('otp_admin_verify');
    }


    public function otp_verify(Request $request)
    {

        $request->validate([

            'otp' => 'required'
        ]);
        #Validation Logic
        $verificationCode   = VerificationCode::where('admin_id', Auth::user()->id)->where('otp', $request->otp)->first();

        $now = Carbon::now();
        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Your OTP is not correct');
        } elseif ($verificationCode && $now->isAfter($verificationCode->expire_at)) {
            return redirect()->back()->with('error', 'Your OTP has been expired');
        }

        $user = Admin::whereId(Auth::user()->id)->first();
        //  dd($user);
        if ($user) {
            // Expire The OTP

            $verificationCode->update([
                'expire_at' => Carbon::now(),
                'verified' => "yes"
            ]);


            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'Your Otp is not correct');
    }
}
