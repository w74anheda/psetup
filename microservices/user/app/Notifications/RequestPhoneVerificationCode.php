<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestPhoneVerificationCode extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $verificationCode)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return [ SmsChannel::class];
    }


    public function toSms(object $notifiable): array
    {
        return [
            'code' => '@' . $this->verificationCode
        ];
    }

    public function viaQueues(): array
    {
        return [
            SmsChannel::class => 'phone-verification-code'
        ];
    }


}
