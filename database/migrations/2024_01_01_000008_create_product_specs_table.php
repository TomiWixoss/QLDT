<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->onDelete('cascade');
            $table->string('screen', 100)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('cpu', 100)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('rom', 50)->nullable();
            $table->string('camera', 150)->nullable();
            $table->string('battery', 100)->nullable();
            $table->string('sim', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specs');
    }
};
