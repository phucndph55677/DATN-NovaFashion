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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng users
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng orders
            $table->decimal('payment_amount', 10, 2); // Số tiền đã thanh toán
            $table->string('payment_method'); // Phương thức thanh toán (ví dụ: chuyển khoản, thẻ tín dụng, ví điện tử,...)
            $table->string('payment_status')->default('pending'); // Trạng thái thanh toán (ví dụ: pending, completed, failed)
            $table->string('transaction_code')->unique(); // Mã giao dịch do hệ thống hoặc cổng thanh toán cung cấp
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
