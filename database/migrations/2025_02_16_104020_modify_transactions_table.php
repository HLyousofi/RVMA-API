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
        Schema::table('transactions', function (Blueprint $table) {
            // 🔹 Rename columns
            $table->renameColumn('unit_price', 'selling_price');
            $table->renameColumn('reseption_date', 'transaction_date');

            // 🔹 Add new columns
            $table->decimal('purchase_price', 8, 2)->nullable();
            $table->string('transaction_type'); 
            $table->unsignedBigInteger('reference_id')->nullable(); 
            $table->text('note')->nullable();

            // 🔹 Foreign key constraint (if reference_id links to another table)
            $table->integer('reference_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //  🔹 Revert column renaming
             $table->renameColumn('selling_price', 'unit_price');
             $table->renameColumn('transaction_date', 'reseption_date');
 
             // 🔹 Remove newly added columns
             $table->dropColumn(['purchase_price', 'transaction_type', 'note']);
 

        });
    }
};
