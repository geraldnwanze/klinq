<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthFormRequest;
use App\Jobs\EmailVerificationJob;
use App\Models\User;
use App\Models\VerifyUserEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(AuthFormRequest $request)
    {
        $user = User::create($request->validated());
        EmailVerificationJob::dispatch($user);
        return redirect(route('login-page'))->with('message', 'Registration successful, A verification link has been sent to your email');
    }

    public function verifyEmail($token)
    {
        $token->user->email_verified = true;
        $token->user->update();
        $token->delete();
        return redirect(route('login'))->with('message', 'Email Verified');
    }

    public function login(AuthFormRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            if (auth()->user()->email_verified) {
                return redirect(route('dashboard'));
            }
            EmailVerificationJob::dispatch(auth()->user());
            Auth::logout();
            return redirect(route('login-page'))->withErrors(['error' => 'Email not verified, verification link has been sent to your email']);
        }
        return redirect(route('login-page'))->withErrors(['error' => 'Invalid Credentials']);
    }
}
