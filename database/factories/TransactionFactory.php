<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Supplier;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $type = $this->faker->randomElement(['Arrivage','Retour']);
        return [
            'product_id' => Product::factory(),
            'supplier_id' => Supplier::factory(),
            'reception_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'unit_price' => $this->faker->numberBetween(10,5000),
            'quantity' => $this->faker->numberBetween(0,200),
            'type' => $type
        ];
    }
}
