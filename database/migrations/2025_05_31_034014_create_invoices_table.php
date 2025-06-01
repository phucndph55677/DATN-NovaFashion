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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết tới bảng orders
            $table->string('invoice_code')->unique(); // Mã hóa đơn, duy nhất
            $table->string('name');    // Tên người nhận hóa đơn
            $table->text('address');   // Địa chỉ nhận hóa đơn
            $table->string('phone');   // Số điện thoại liên hệ
            $table->string('email');   // Email liên hệ
            $table->tinyInteger('status')->default(0); // Trạng thái hóa đơn (ví dụ: 0-chưa thanh toán, 1-đã thanh toán, 2-hủy)
            $table->decimal('total', 10, 2);            // Tổng tiền trên hóa đơn
            $table->date('issue_date');                 // Ngày xuất hóa đơn
            $table->text('note')->nullable();           // Ghi chú thêm (nếu có)
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
