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
        Schema::table('quotes', function (Blueprint $table) {
            // Add new columns
            $table->date('creation_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->longText('comment')->nullable();
            $table->unsignedBigInteger('vehicle_id')->change();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');

            // Remove unnecessary columns
            $table->dropColumn('amount');
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
