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
        Schema::create('banners', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('name'); // Tên banner
            $table->string('image'); // Đường dẫn hoặc tên file ảnh banner
            $table->tinyInteger('status')->default(1); // Trạng thái banner (1: hiển thị, 0: ẩn)
            $table->string('product_link')->nullable(); // Link sản phẩm khi bấm vào banner (có thể null)
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
        Schema::dropIfExists('banners');
    }
};
