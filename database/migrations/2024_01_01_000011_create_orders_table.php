<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 50)->unique();
            $table->enum('source', ['web', 'store'])->default('web');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total_money', 15, 2);
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'cod'])->default('cod');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('order_status', ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->string('shipping_name', 100)->nullable();
            $table->string('shipping_phone', 15)->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_carrier', 50)->nullable();
            $table->string('tracking_code', 50)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('order_code');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
