<?php

namespace App\Http\Middleware;

use App\Models\VerifyUserEmail;
use Closure;
use Illuminate\Http\Request;

class ValidateEmailVerificationToken
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
        if ($request->token) {
            if (VerifyUserEmail::where('token', $request->token)->exists()) {
                return $next($request);
            }
            return redirect(route('login'))->withErrors(['error' => 'Token is Invalid']);
        }
        return redirect(route('login'))->withErrors(['error' => 'Token is Invalid']);
    }
}
