<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Otp\OtpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Otp\AdminOtpController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/otp-send', [OtpController::class, 'otp_send'])->name('otp_user_send');
Route::get('/otp-verify', function () {
    return view("auth.otp_login_user");
})->name('otp_user_verify');
Route::post('/otp-verify', [OtpController::class, 'otp_verify'])->name('otp_user_handle');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->group(function () {
    // Login Routes
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::get('/register', [AdminRegisterController::class, 'register_form'])->name('admin.register');
    Route::post('/register', [AdminRegisterController::class, 'create'])->name('admin.register');

    // Dashboard Route
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    //otp routes
    Route::get('/otp-send', [AdminOtpController::class, 'otp_send'])->name('otp_admin_send');
    Route::get('/otp-verify', [AdminOtpController::class,'otp_verify_form'])->name('otp_admin_verify');
    Route::post('/otp-verify', [AdminOtpController::class, 'otp_verify'])->name('otp_admin_handle');
});
