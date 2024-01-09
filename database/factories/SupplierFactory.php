<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'adress' => $this->faker->address(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->phoneNumber(),
            'ice' => $this->faker->regexify('[0-9]{15}'),
            'rc' => $this->faker->regexify('[0-9]{8}')
        ];
    }
}
