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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // FK tới categories
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade'); // FK tới roles
            $table->string('product_code')->unique(); // Mã sản phẩm, duy nhất
            $table->string('name'); // Tên sản phẩm
            $table->string('image')->nullable(); // Ảnh (có thể để trống)
            $table->text('description')->nullable(); // Mô tả
            $table->string('material')->nullable(); // Chất liệu
            $table->boolean('onpage')->default(true); // Có hiển thị trang chính không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
