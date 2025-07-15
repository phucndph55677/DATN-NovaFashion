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
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng users
            $table->foreignId('voucher_id')->nullable()->constrained()->onDelete('set null'); // Khóa ngoại liên kết tới bảng vouchers
            $table->integer('quantity')->default(0); // Tổng số lượng sản phẩm trong giỏ
            $table->decimal('subtotal', 10, 2)->default(0); // Tổng trước giảm
            $table->decimal('discount', 10, 2)->default(0); // Số tiền giảm giá
            $table->decimal('total_amount', 10, 2)->default(0); // Tổng sau giảm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};