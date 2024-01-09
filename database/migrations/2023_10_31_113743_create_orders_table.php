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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('vehicle_id');
            $table->integer('invoice_id');
            $table->integer('quote_id');
            $table->string('name');
            $table->string('description');
            $table->integer('price');
            $table->json('task')->nullable();//{description : prix} 
            $table->string('status');//En attente,en cours,termine,facturé
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
