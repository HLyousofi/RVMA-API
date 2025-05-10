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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->constrained('customers');
            $table->unsignedBigInteger('vehicle_id')->constrained('vehicles');
            $table->integer('amount');
            $table->string('status');//facturé, payé
            $table->date('billed_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->unsignedBigInteger('workorder_id')->constrained('work_orders')->onDelete('set null')->nullable();
            // $table->foreign('workorder_id')->constrained('work_orders')->onDelete('set null');
            $table->timestamps();
            $table->string('invoice_number')->unique();
            $table->decimal('discount', 10, 2)->nullable();
            // $table->foreign('customer_id')
            // ->references('id')->on('customers')
            // ->onDelete('restrict');

            // $table->foreign('vehicle_id')
            // ->references('id')->on('vehicles')
            // ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
