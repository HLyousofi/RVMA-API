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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('workorder_number')->unique()->nullable();
            $table->unsignedBigInteger('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->unsignedBigInteger('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->string('quote_number')->nullable();
            $table->string('status');
            $table->enum('type', ['quote', 'order'])->default('quote');
            $table->unsignedInteger('current_mileage')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('remise', 10, 2)->default(0);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
            $table->date('expiration_date')->nullable();
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->longText('comment')->nullable();
            $table->timestamps();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
