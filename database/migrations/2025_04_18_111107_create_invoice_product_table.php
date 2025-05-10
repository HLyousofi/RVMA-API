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
        Schema::create('invoice_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->constrained('invoices')->onDelete('set null'); // Foreign key to invoices table
            $table->unsignedBigInteger('product_id')->constrained('products')->onDelete('set null'); // Foreign key to products table
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_price', 10, 2)->storedAs('(quantity * unit_price)');
            $table->timestamps();

            // Define foreign keys
            // $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
