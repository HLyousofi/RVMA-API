<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $productNames = [
            'Plaquette de frein',
            'Disque de frein',
            'Étrier de frein',
            'Filtre à huile',
            'Filtre à air',
            'Filtre à carburant',
            'Filtre d’habitacle',
            'Bougie d’allumage',
            'Bougie de préchauffage',
            'Courroie de distribution',
            'Kit de distribution',
            'Pompe à eau',
            'Amortisseur',
            'Ressort de suspension',
            'Rotule de direction',
            'Biellette de direction',
            'Triangle de suspension',
            'Cardan',
            'Embrayage',
            'Volant moteur',
            'Boîte de vitesses',
            'Radiateur',
            'Ventilateur de refroidissement',
            'Batterie',
            'Alternateur',
            'Démarreur',
            'Pompe à carburant',
            'Sonde lambda',
            'Capteur ABS',
            'Injecteur',
            'Turbo',
            'Silencieux',
            'Catalyseur',
            'Pot d’échappement',
            'Essuie-glace',
            'Pompe de lave-glace',
            'Optique de phare',
            'Clignotant',
            'Rétroviseur extérieur',
            'Poignée de porte',
            'Capot',
            'Pare-chocs',
            'Pare-brise',
        ];

        // Exemple : insérer 50 pièces de voiture
        foreach ($productNames as $name) {
            DB::table('products')->insert([
                'name' =>$name,
                'category_id' => $faker->numberBetween(1, 10), // Suppose que tu as des catégories de 1 à 10
                'brand' => $faker->randomElement(['Bosch', 'Valeo', 'NGK', 'Denso', 'Delphi']),
                'model' => $faker->randomElement(['Universal', 'Toyota Corolla', 'Ford Focus', 'BMW X5', 'VW Golf']),
                'manufacturer_reference' => 'MFR-' . $faker->unique()->bothify('???###'),
                'oem_reference' => 'OEM-' . $faker->unique()->bothify('??##-####'),
                'description' => $faker->sentence(10),
                'purchase_price' => $faker->randomFloat(2, 10, 100), // Prix d'achat entre 10 et 100
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
       
    }
}
