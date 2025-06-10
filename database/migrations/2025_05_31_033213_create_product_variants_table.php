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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng products
            $table->foreignId('color_id')->constrained()->onDelete('cascade');   // Khóa ngoại liên kết tới bảng colors
            $table->foreignId('size_id')->constrained()->onDelete('cascade');    // Khóa ngoại liên kết tới bảng sizes
            $table->string('image')->nullable(); // Đường dẫn hoặc tên file ảnh, có thể để trống
            $table->integer('price'); // VD: 150000 (150.000 VND)
            $table->integer('sale')->nullable(); // Có thể để trống 
            $table->integer('quantity')->default(0);    // Số lượng tồn kho, mặc định 0
            $table->boolean('is_active')->default(true); // Trạng thái biến thể sản phẩm: còn bán, dừng bán
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
