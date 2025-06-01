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
            $table->foreignId('role_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng roles
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng users
            $table->string('name');          // Tên voucher
            $table->string('voucher_code')->unique(); // Mã voucher, duy nhất
            $table->integer('quantity')->default(0);  // Số lượng voucher còn lại
            $table->decimal('sale_price', 10, 2);     // Giá trị giảm giá
            $table->decimal('min_price', 10, 2);      // Giá tối thiểu đơn hàng được áp dụng voucher
            $table->decimal('max_price', 10, 2)->nullable(); // Giá tối đa được giảm (có thể null)
            $table->date('start_date');      // Ngày bắt đầu áp dụng voucher
            $table->date('end_date');        // Ngày kết thúc áp dụng voucher
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
