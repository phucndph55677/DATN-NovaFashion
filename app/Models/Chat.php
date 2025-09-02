<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'last_message_at',
    ];

    // Khách hàng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Admin phụ trách
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Tin nhắn trong chat
    public function chatDetails()
    {
        return $this->hasMany(ChatDetail::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatDetail::class, 'chat_id')->latestOfMany();
    }
}
