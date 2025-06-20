<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Tạo một thể hiện email mới.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Xây dựng thông điệp email.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Mật khẩu của bạn đã được thay đổi thành công!')
                    ->view('admin.auth.passwords.password_changed'); // Tên view bạn sẽ sử dụng để gửi email
    }
}
