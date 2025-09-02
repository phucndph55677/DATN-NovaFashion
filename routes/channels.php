<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Chat channel - chỉ admin và user trong chat mới có thể join
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);

    if (!$chat) {
        return false;
    }

    // Admin có thể join tất cả chat
    if ($user->role_id == 1) {
        return true;
    }

    // User chỉ có thể join chat của mình
    return $chat->user_id == $user->id;
});
