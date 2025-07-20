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
            $table->integer('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('name')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->decimal('purchase_price',8, 2)->default(0.00);
            $table->decimal('selling_price', 8, 2)->default(0.00);
            $table->string('manufacturer_reference')->unique()->nullable();
            $table->string('oem_reference')->nullable();
            $table->string('description')->nullable();
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
