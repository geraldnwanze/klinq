<?php

namespace App\Jobs;

use App\Mail\EmailVerificationMail;
use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Models\VerifyUserEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $route;
    private $name;
    private $token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->route = request()->route()->getName();
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {    
        match($this->route){
            'auth.register' => $this->jobForRegister(),
            'auth.login' => $this->jobForLogin(),
            'auth.forgot-password' => $this->jobForPasswordReset()
        };
    }

    public function jobForRegister()
    {
       if ($this->createEmailVerificationToken()) {
           $this->sendEmailVerificationMail();
       }
    }

    public function jobForLogin()
    {
        if ($this->updateEmailVerificationToken()) {
            $this->sendEmailVerificationMail();
        }
    }

    public function jobForPasswordReset()
    {
        if ($this->createPasswordResetToken()) {
            $this->sendPasswordResetMail();
        }
    }

    public function createPasswordResetToken()
    {
        $token = Str::random(64);
        $name = User::where('email', $this->user['email'])->first()->name;
        $password_reset = DB::table('password_resets')->where('email', $this->user['email'])->first();
        if ($password_reset === null) {
            DB::table('password_resets')->insert(['email' => $this->user['email'], 'token' => $token, 'created_at' => now()]);
        } else {
            DB::table('password_resets')->where('email', $this->user['email'])->update(['token' => $token]);
        }
        $this->name = $name;
        $this->token = $token;
        return true;
    }

    public function sendPasswordResetMail()
    {
        Mail::to($this->user['email'])->send(new PasswordResetMail($this->name, $this->token));
    }

    public function createEmailVerificationToken()
    {
        if (VerifyUserEmail::create(['user_id' => $this->user->id, 'token' => Str::random(64), 'expire_at' => now()])) {
            return true;
        }
        return false;
    }

    public function updateEmailVerificationToken()
    {
        if (VerifyUserEmail::where('user_id', $this->user->id)->first()->update(['token' => Str::random(64), 'expire_at' => now()])) {
            return true;
        }
        return false;
    }

    public function sendEmailVerificationMail()
    {
        Mail::to($this->user->email)->send(new EmailVerificationMail($this->user));
    }
}
