<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidatePasswordResetToken
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
            if (DB::table('password_resets')->where('token', $request->token)->exists()) {
                if (DB::table('password_resets')->where('token', $request->token)->first()->expire_at > time()) {
                    return $next($request);
                }
                return redirect(route('auth.login-page'))->withErrors(['error' => 'Token has expired']);
            }
            return redirect(route('auth.login-page'))->withErrors(['error' => 'Token is Invalid']);
        }
        return redirect(route('auth.login-page'))->withErrors(['error' => 'Token not provided']);
    }
}
