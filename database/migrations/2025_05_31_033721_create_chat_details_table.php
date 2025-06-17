<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_details', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('chat_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng chats
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('message'); // Nội dung chi tiết tin nhắn
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_details');
    }
};
