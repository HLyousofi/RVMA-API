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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->constrained('customers')->onDelete('set null');
            $table->unsignedBigInteger('brand_id')->constrained('car_brands')->onDelete('set null');
            $table->foreignId('fueltype_id')->nullable()->constrained('fuel_types')->onDelete('set null');
            $table->string('model');
            $table->string('plate_number');
            $table->timestamps();
            // $table->foreign('brand_id')->references('id')->on('car_brands')->onDelete('cascade');
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
