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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng products
            $table->foreignId('user_id')->constrained()->onDelete('cascade');    // Khóa ngoại liên kết tới bảng users
            $table->tinyInteger('rating')->unsigned(); // Đánh giá (1-5), kiểu số nguyên nhỏ không dấu
            $table->text('content')->nullable();       // Nội dung đánh giá, có thể để trống
            $table->tinyInteger('status')->default(1); // 0 = chưa duyệt, 1 = hiển thị
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
