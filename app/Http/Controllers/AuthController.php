<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthFormRequest;
use App\Jobs\AuthJob;
use App\Models\User;
use App\Models\VerifyUserEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(AuthFormRequest $request)
    {
        $user = User::create($request->validated());
        AuthJob::dispatch($user);
        return redirect(route('auth.login-page'))->with('message', 'Registration successful, A verification link has been sent to your email');
    }

    public function verifyEmail($token)
    {
        $token = VerifyUserEmail::where('token', $token)->first();
        $token->user->email_verified = true;
        $token->user->update();
        $token->delete();
        return redirect(route('auth.login-page'))->with('message', 'Email Verified');
    }

    public function login(AuthFormRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            if (auth()->user()->email_verified) {
                return redirect(route('dashboard'));
            }
            AuthJob::dispatch(auth()->user());
            Auth::logout();
            return redirect(route('auth.login-page'))->withErrors(['error' => 'Email not verified, verification link has been sent to your email']);
        }
        return redirect(route('auth.login-page'))->withErrors(['error' => 'Invalid Credentials']);
    }

    public function forgotPassword(AuthFormRequest $request)
    {   
        if (User::where('email', $request->validated())->exists()) {
            AuthJob::dispatch($request->validated());
            return redirect(route('auth.login-page'))->with('message', 'A password reset link has been send to your email');
        }
        return back()->withErrors(['error' => 'invalid email provided']);
    }

    public function resetPassword(AuthFormRequest $request, $token)
    {
        $password_reset = DB::table('password_resets')->where('token', $token);
        $email = $password_reset->first()->email;
        $user = User::where('email', $email)->first();
        $user->password = $request->password;
        if ($user->update()) {
            $password_reset->delete();
            return redirect(route('auth.login'))->with('message', 'Password Updated');
        }
        return back()->withErrors(['error' => 'an error occurred']);
    }
}
