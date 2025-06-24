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
        Schema::create('product_photo_albums', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng products
            $table->string('image')->nullable(); // Đường dẫn hoặc tên file ảnh, có thể để trống
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_photo_albums');
    }
};
