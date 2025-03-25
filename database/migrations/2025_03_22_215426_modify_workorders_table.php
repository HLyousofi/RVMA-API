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
        
        Schema::table('work_orders', function (Blueprint $table) {
            $table->enum('type', ['quote', 'order'])->default('quote')->after('vehicle_id');
            $table->unsignedBigInteger('customer_id')->nullable()->after('type');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->decimal('total', 10, 2)->nullable()->after('status');
            $table->unsignedBigInteger('invoice_id')->nullable()->after('total');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
            $table->date('order_date')->nullable()->after('expiration_date');
            $table->date('delivery_date')->nullable()->after('order_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workorders', function (Blueprint $table) {
            //
        });
    }
};
