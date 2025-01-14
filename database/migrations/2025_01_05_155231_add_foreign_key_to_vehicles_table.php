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
        Schema::table('vehicles', function (Blueprint $table) {
              // Ensure the `customer_id` column exists or add it
              $table->unsignedBigInteger('customer_id')->nullable()->change();

              // Add the foreign key constraint
              $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);

            // Optionally revert the column
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
    }
};
