<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Générer 100 véhicules
        for ($i = 0; $i < 100; $i++) {
            // Générer un plate_number au format : 1-5 chiffres + [A,B,C,D,E] + 2 chiffres (01-99)
            $digits = $faker->numberBetween(1, 99999); // Jusqu'à 5 chiffres
            $letter = $faker->randomElement(['A', 'B', 'C', 'D', 'E']);
            $twoDigits = str_pad($faker->numberBetween(1, 99), 2, '0', STR_PAD_LEFT); // 01 à 99

            DB::table('vehicles')->insert([
                'customer_id' => $faker->numberBetween(1, 50), // IDs des clients (1 à 50)
                'brand_id' => $faker->numberBetween(1, 90), // IDs des marques (1 à 90)
                'model' => $faker->randomElement(['Corolla', 'Civic', 'Focus', 'Golf', 'X5', 'A4', 'Tiguan']),
                'plate_number' => $digits . $letter . $twoDigits, // Ex. : 123A45
                'fueltype_id' => $faker->numberBetween(1, 4), // IDs des types de carburant (1 à 4)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
      
    }
}
