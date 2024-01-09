<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vehicle;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotes>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['D', 'S', 'A', 'R', 'C']);
        return [
            'vehicle_id' => Vehicle::Factory(),
            'amount' => $this->faker->numberBetween(100,20000),
            'status' => $status
        ];
    }
}
