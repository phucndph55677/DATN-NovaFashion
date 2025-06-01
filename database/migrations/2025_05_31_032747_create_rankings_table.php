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
        Schema::create('rankings', function (Blueprint $table) {
            $table->id(); // Khóa chính id
            $table->string('name')->unique();  // Tên cấp bậc (ví dụ: Kim Cương, Vàng, Bạc,...)
            $table->integer('level')->unique(); // Cấp độ xếp hạng duy nhất (không trùng giữa các user)
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
