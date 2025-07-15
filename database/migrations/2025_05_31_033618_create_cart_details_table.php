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
        Schema::create('cart_details', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('cart_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng carts
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng product_variant
            $table->integer('quantity')->default(1); // Số lượng sản phẩm chi tiết trong giỏ
            $table->decimal('price', 10, 2)->default(0); // Giá sản phẩm tại thời điểm thêm vào giỏ chi tiết
            $table->decimal('total_amount', 10, 2)->default(0); // giá * số lượng
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_details');
    }
};