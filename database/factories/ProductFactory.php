<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement(['Filtre à air ', 'Filtre à Huile', 'Filtre à Gazoil', 'Huile 5/30', 'Huile 5/40' ]);
        return [
            'category_id' => Category::factory(), 
            'name' => $name,
            'description' => $this->faker->text(20),
            'referance' => $this->faker->text(5),
            'price' => $this->faker->numberBetween(100,5000),
            'unit_in_stock' => $this->faker->numberBetween(0,200)
        ];
    }
}
