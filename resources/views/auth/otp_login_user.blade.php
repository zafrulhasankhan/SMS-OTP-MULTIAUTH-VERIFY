@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login verification') }}</div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert"> {{session('success')}}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger" role="alert"> {{session('error')}}
                    </div>
                    @endif
                    <div class="alert alert-secondary" role="alert">
                        Please enter the verification code sent to your mobile number
                        <?php

                        use Illuminate\Support\Facades\Auth;

                        $phone_number = Auth::user()->mobile_no;
                        $masked_number = substr($phone_number, 0, 3) . "*****" . substr($phone_number, -3);
                        echo $masked_number; 
                        ?>
                    </div>
                    <form method="POST" action="{{ route('otp_user_handle') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="otp" class="col-md-4 col-form-label text-md-end">{{ __('OTP') }}</label>

                            <div class="col-md-6">
                                <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="otp">

                                @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>




                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verify OTP') }}
                                </button>
                                <a href="{{ route('otp_user_send') }}" class="btn btn-primary">
                                    {{ __('Resend OTP Code') }}
                                </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection