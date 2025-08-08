<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Mật khẩu của bạn đã được đổi')
            ->view('client.emails.password_reset')
            ->with([
                'user' => $this->user,
            ]);
    }
}
