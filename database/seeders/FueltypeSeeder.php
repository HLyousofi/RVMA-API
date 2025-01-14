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
            ['fuel_type' => 'Petrol'],
            ['fuel_type' => 'Diesel'],
            ['fuel_type' => 'Electric'],
            ['fuel_type' => 'Hybrid'],
        ]);
    }
}
