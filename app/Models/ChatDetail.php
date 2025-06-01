<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatDetail extends Model
{
    /** @use HasFactory<\Database\Factories\ChatDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'content',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
