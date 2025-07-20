<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plaquettes de frein',
                'description' => 'Plaquettes de frein haute performance pour une puissance de freinage optimale et une sécurité accrue.',
            ],
            [
                'name' => 'Filtres à huile',
                'description' => 'Filtres à huile conçus pour éliminer les impuretés de l’huile moteur, prolongeant la durée de vie du moteur.',
            ],
            [
                'name' => 'Bougies d’allumage',
                'description' => 'Bougies d’allumage pour une combustion efficace et des performances moteur optimales.',
            ],
            [
                'name' => 'Filtres à air',
                'description' => 'Filtres à air protégeant le moteur en retenant la saleté et les débris de l’air entrant.',
            ],
            [
                'name' => 'Embrayages',
                'description' => 'Kits et composants d’embrayage pour des changements de vitesse fluides et une fiabilité de la transmission.',
            ],
            [
                'name' => 'Batteries',
                'description' => 'Batteries automobiles offrant une puissance de démarrage fiable et un soutien électrique.',
            ],
            [
                'name' => 'Amortisseurs',
                'description' => 'Amortisseurs pour un confort de conduite amélioré et une stabilité du véhicule.',
            ],
            [
                'name' => 'Courroies de distribution',
                'description' => 'Courroies de distribution pour synchroniser les composants du moteur et optimiser les performances.',
            ],
            [
                'name' => 'Radiateurs',
                'description' => 'Radiateurs pour un refroidissement efficace du moteur et une régulation de la température.',
            ],
            [
                'name' => 'Alternateurs',
                'description' => 'Alternateurs générant l’énergie électrique pour les systèmes du véhicule et la recharge de la batterie.',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
       
    }
}
