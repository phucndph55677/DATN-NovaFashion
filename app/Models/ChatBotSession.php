<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBotSession extends Model
{
    /** @use HasFactory<\Database\Factories\ChatBotSessionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_code',
        'title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
