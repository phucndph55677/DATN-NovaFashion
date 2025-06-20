<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink; // Thêm biến để chứa resetLink

    /**
     * Create a new message instance.
     *
     * @param string $resetLink
     * @return void
     */
    public function __construct($resetLink)
    {
        $this->resetLink = $resetLink; // Gán giá trị resetLink
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.auth.passwords.reset_email') // Sử dụng view `reset_email` để gửi email
                    ->with(['resetLink' => $this->resetLink]); // Truyền resetLink vào view
    }
}
 