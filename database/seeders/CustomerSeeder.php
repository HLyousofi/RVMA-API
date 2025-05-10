<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Générer 50 clients
        for ($i = 0; $i < 50; $i++) {
            $type = $faker->randomElement(['B', 'I']); // Type B (Business) ou I (Individuel)
            $ice = null;

            // Si type est I (Individuel), générer un ICE unique de 10 chiffres
            if ($type === 'B') {
                $ice = $faker->unique()->numerify('##########'); // 10 chiffres uniques
            }

            DB::table('customers')->insert([
                'name' => $type === 'B' ? $faker->company : $faker->name,
                'type' => $type,
                'adress' => $faker->address, // Adresse avec "adress" comme spécifié
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->unique()->phoneNumber,
                'ice' => $ice, // ICE pour Individuel, null pour Business
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
    }
}
