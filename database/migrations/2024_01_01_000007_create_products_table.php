<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('brand_id')->constrained('brands');
            $table->string('sku', 50)->unique()->comment('Mã kho/Barcode');
            $table->string('name', 200);
            $table->decimal('price', 12, 2)->comment('Giá bán');
            $table->decimal('cost', 12, 2)->default(0)->comment('Giá vốn');
            $table->integer('quantity')->default(0);
            $table->string('image', 255)->nullable();
            $table->integer('warranty_months')->default(12)->comment('Bảo hành (tháng)');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
