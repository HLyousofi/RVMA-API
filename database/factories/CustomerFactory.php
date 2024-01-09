<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['I', 'B']);
        $name = $type == 'I' ? $this->faker->name() : $this->faker->company();
        $identificat = $type == 'I' ? 'CIN':'ICE';
        $info = array($identificat => $this->faker->numberBetween(1000000, 9999999));
        return [
            'name' => $name,
            'type' => $type,
            'email' => $this->faker->email(),
            'adress' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber(),
            'custom_info' =>json_encode($info)
        ];
    }
}
