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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng orders
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade'); // Khóa ngoại liên kết tới bảng product_variants  
            $table->decimal('price', 10, 2); // Giá sản phẩm tại thời điểm đặt hàng
            $table->integer('quantity')->default(1); // Số lượng sản phẩm
            $table->tinyInteger('status')->default(0); // Trạng thái sản phẩm trong đơn (ví dụ: 0-chờ xử lý, 1-đang giao, 2-hoàn thành, 3-hủy)
            $table->date('estimated_delivery')->nullable(); // Ngày dự kiến giao hàng, có thể để trống
            $table->text('note')->nullable(); // Ghi chú riêng cho sản phẩm trong đơn
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
