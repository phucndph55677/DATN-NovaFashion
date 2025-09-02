<?php

namespace Database\Seeders;

use App\Models\ChatBotMessage;
use App\Models\ChatBotSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatBotMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả session có sẵn
        $sessions = ChatBotSession::all();

        foreach ($sessions as $session) {
            // Tạo 10 message cho mỗi session
            ChatBotMessage::factory()->count(10)->create([
                'chat_bot_session_id' => $session->id
            ]);
        }

    }
}
