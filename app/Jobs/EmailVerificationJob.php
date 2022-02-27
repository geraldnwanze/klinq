<?php

namespace App\Jobs;

use App\Mail\EmailVerificationMail;
use App\Models\VerifyUserEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $route;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->route = request()->path();
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
            'register' => $this->jobForRegister()
        };
    }

    public function jobForRegister()
    {
       if ($this->createEmailVerificationToken()) {
           $this->sendEmailVerificationMail();
       }
    }

    public function createEmailVerificationToken()
    {
        if (VerifyUserEmail::create(['user_id' => $this->user->id, 'token' => Str::random(64), 'expire_at' => now()])) {
            return true;
        }
        return false;
    }

    public function sendEmailVerificationMail()
    {
        Mail::to($this->user->email)->send(new EmailVerificationMail($this->user));
    }
}
