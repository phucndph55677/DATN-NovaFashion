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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('voucher_code')->unique(); // Mã voucher, duy nhất
            $table->integer('quantity')->default(0);  // Số lượng voucher được tung ra
            $table->integer('total_used')->default(0); // Số lượng voucher đã sử dụng
            $table->integer('user_limit')->default(1); // Số lần 1 user được sử dụng
            $table->decimal('sale_price', 10, 2);     // Giá trị giảm giá
            $table->decimal('max_discount', 10, 2); // Giảm giá tối đa
            $table->decimal('min_price', 10, 2);      // Giá tối thiểu đơn hàng được áp dụng voucher
            $table->tinyInteger('status')->default(1); // 1: active, 0: inactive
            $table->text('description')->nullable();   // Mô tả chi tiết voucher
            $table->dateTime('start_date');      // Ngày bắt đầu áp dụng voucher (có giờ)
            $table->dateTime('end_date');        // Ngày kết thúc áp dụng voucher (có giờ)
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
