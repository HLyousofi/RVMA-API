<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FueltypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('fuel_types')->insert([
            ['fuel_type' => 'Petrol', 'created_at' => now(), 'updated_at' => now(),],
            ['fuel_type' => 'Diesel','created_at' => now(),'updated_at' => now()],
            ['fuel_type' => 'Electric', 'created_at' => now(),'updated_at' => now(),],
            ['fuel_type' => 'Hybrid','created_at' => now(),'updated_at' => now()],
        ]);
    }
}
