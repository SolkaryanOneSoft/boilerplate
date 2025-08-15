<?php

namespace App\Jobs;

use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $locale;

    public function __construct(User $user, string $locale = 'en')
    {
        $this->user = $user;
        $this->locale = $locale;
    }

    public function handle(): void
    {
        App::setLocale($this->locale);

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(30),
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->getEmailForVerification()),
            ]
        );


        $cleanPathUrl = str_replace('/api/verify-email', '/verify-email', $verifyUrl);

        $frontendUrl = config('frontend.url');

        $verifyUrl = preg_replace(
            '/^http:\/\/[^\/]+/',
            $frontendUrl,
            $cleanPathUrl
        );


        Mail::to($this->user->email)->send(new VerificationEmail($verifyUrl));
    }
}
