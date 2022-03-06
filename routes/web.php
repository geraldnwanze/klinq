<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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
})->name('home');

# Auth Routes

Route::prefix('auth')->name('auth.')->group(function () {
    Route::view('register', 'auth.register')->name('register-page');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('verify-email/{token}', [AuthController::class, 'verifyEmail'])
        ->name('verify-email')
        ->middleware('validate-email-verification-token');

    Route::view('login', 'auth.login')->name('login-page');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::view('forgot-password', 'auth.forgot-password')->name('forgot-password');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::get('reset-password/{token}', function ($token) {
            return view('auth.reset-password', compact('token'));
        })->name('reset-password-page')
            ->middleware('validate-password-reset-token');
    Route::put('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('reset-password');
});
