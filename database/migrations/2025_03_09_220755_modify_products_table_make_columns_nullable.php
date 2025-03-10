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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('category_id')->nullable()->change();
            $table->text('selling_price')->nullable()->change();
            $table->decimal('purchase_price', 8, 2)->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->string('description')->nullable()->change();
        });
           
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // $table->integer('category_id')->nullable()->change();
         
        });
    }

};
