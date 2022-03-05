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
});

# Auth Routes
Route::view('register', 'auth.register')->name('register-page');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify-email')->middleware('verify-email');

Route::view('login', 'auth.login')->name('login-page');
Route::post('login', [AuthController::class, 'login'])->name('login');