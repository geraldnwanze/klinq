<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthFormRequest;
use App\Jobs\EmailVerificationJob;
use App\Models\User;
use App\Models\VerifyUserEmail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(AuthFormRequest $request)
    {
        $user = User::create($request->validated());
        EmailVerificationJob::dispatch($user);
        return redirect(route('login'));
    }
}
