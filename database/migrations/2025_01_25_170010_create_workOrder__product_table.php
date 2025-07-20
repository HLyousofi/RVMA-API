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
        Schema::create('workorder_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id')->constrained('work_orders')->onDelete('set null'); // Foreign key to quotes table
            $table->unsignedBigInteger('product_id')->constrained('products')->onDelete('set null'); // Foreign key to products table
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_price', 10, 2)->storedAs('(quantity * unit_price)');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workorder_product');
    }
};
