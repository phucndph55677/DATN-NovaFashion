<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    // Chỉ định bảng mà model này làm việc với
    protected $table = 'password_reset_tokens';

    // Cấu hình các trường có thể được gán (mass assignable)
    protected $fillable = ['email', 'token', 'created_at'];

    // Để chỉ định bảng không có cột `id` mặc định
    public $incrementing = false;

    // Để xác định rằng bảng này không sử dụng cột `id` mà là cột khác
    protected $primaryKey = 'email'; // Sử dụng email làm khóa chính

    // Nếu bảng không có các trường `created_at` và `updated_at`, đặt giá trị này là false
    public $timestamps = false;
}

