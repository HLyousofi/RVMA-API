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
            // $table->string('invoice_number')->unique();
            // $table->decimal('discount', 10, 2)->nullable();
            // $table->foreignId('vehicle_id')->constrained()->onDelete('restrict');
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
