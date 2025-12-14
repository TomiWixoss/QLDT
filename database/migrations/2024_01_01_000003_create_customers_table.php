<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 100)->unique();
            $table->string('password', 255)->nullable()->comment('Mật khẩu Hash');
            $table->string('full_name', 100);
            $table->string('phone', 15)->nullable();
            $table->string('avatar', 255)->nullable()->comment('Link ảnh');
            $table->string('google_id', 50)->unique()->nullable()->comment('ID từ Google API');
            $table->string('facebook_id', 50)->unique()->nullable()->comment('ID từ Facebook API');
            $table->integer('points')->default(0)->comment('Điểm tích lũy');
            $table->text('address')->nullable()->comment('Địa chỉ giao hàng mặc định');
            $table->string('city', 100)->nullable();
            $table->enum('status', ['active', 'locked'])->default('active');
            $table->timestamps();

            $table->index('email');
            $table->index('google_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
