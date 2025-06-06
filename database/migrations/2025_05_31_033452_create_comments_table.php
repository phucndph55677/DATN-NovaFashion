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
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng products
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng users
            $table->text('content'); // Nội dung bình luận
            $table->tinyInteger('status')->default(0); // Trạng thái bình luận (0: chờ duyệt, 1: hiển thị, 2: ẩn)
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
