<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatBotMessagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_bot_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_bot_session_id')->constrained('chat_bot_sessions')->onDelete('cascade');
            $table->enum('sender_type', ['user', 'bot']);
            $table->text('message');
            $table->integer('tokens_used')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_bot_messages');
    }
}