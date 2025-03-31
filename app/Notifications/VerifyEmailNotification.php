<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use App\Mail\VerificationEmail;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        Log::info('Verification URL generated:', [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'url' => $verificationUrl
        ]);

        return new VerificationEmail($notifiable, $verificationUrl);
    }

    protected function verificationUrl($notifiable): string
    {
        $baseUrl = config('app.frontend_url', config('app.url'));
        
        $temporarySignedRoute = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // Extract query parameters from the signed URL
        $parsedUrl = parse_url($temporarySignedRoute);
        $queryParams = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }

        // Construct frontend URL with verification parameters
        return $baseUrl . '/verify-email?' . http_build_query($queryParams);
    }
} 