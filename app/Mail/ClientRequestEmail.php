<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Đặt lại mật khẩu NovaFashion')
            ->view('client.emails.request') // KHÔNG PHẢI LÀ GIAO DIỆN TRANG CLIENT!
            ->with([
                'user' => $this->user,
                'token' => $this->token
            ]);
    }
}
