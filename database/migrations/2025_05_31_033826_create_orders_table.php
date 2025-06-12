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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng users
            $table->foreignId('order_status_id')->constrained()->onDelete('restrict'); // hoặc cascade nếu cần
            $table->string('order_code')->unique(); // Mã đơn hàng, duy nhất
            $table->string('name');    // Tên người nhận
            $table->text('address');   // Địa chỉ giao hàng
            $table->string('phone');   // Số điện thoại liên hệ
            $table->string('email');   // Email người nhận
            $table->decimal('total', 10, 2);      // Tổng tiền chưa giảm giá
            $table->decimal('sale_price', 10, 2)->default(0); // Tổng tiền giảm giá (nếu có)
            $table->string('voucher_code')->nullable(); // Mã voucher (nếu có)
            $table->string('payment');            // Phương thức thanh toán (ví dụ: tiền mặt, thẻ tín dụng,...)
            $table->decimal('total_amount', 10, 2); // Tổng tiền cuối cùng phải thanh toán
            $table->text('note')->nullable();    // Ghi chú thêm (nếu có)
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
