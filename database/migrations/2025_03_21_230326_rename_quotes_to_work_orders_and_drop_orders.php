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
         // Renommer la table quotes en work_orders
         Schema::rename('quotes', 'work_orders');

         // Supprimer la table orders
         Schema::dropIfExists('orders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workOrder_product', function (Blueprint $table) {
            if (Schema::hasColumn('workOrder_product', 'quote_id')) {
                $table->renameColumn('quote_id', 'workOrder_id');
            }
        });
    }
};

