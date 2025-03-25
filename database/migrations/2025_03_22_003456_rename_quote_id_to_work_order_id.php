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
        Schema::table('workOrder_product', function (Blueprint $table) {
            if (Schema::hasColumn('workOrder_product', 'quote_id')) {
                $table->renameColumn('quote_id', 'workOrder_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_order_id', function (Blueprint $table) {
            //
        });
    }
};
