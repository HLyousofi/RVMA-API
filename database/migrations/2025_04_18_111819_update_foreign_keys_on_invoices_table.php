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
        Schema::table('invoices', function (Blueprint $table) {
            // Ensure columns exist and are unsigned bigints
            $table->unsignedBigInteger('customer_id')->change();
            $table->unsignedBigInteger('vehicle_id')->change();

            // Add foreign keys
            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('restrict');

            $table->foreign('vehicle_id')
                ->references('id')->on('vehicles')
                ->onDelete('restrict');
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
