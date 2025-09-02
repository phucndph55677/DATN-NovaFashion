<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBotMessage extends Model
{
    /** @use HasFactory<\Database\Factories\ChatBotMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'chat_bot_session_id',
        'sender_type',
        'message',
        'tokens_used',
    ];

    public function session()
    {
        return $this->belongsTo(ChatBotSession::class, 'chat_bot_session_id');
    }
}
