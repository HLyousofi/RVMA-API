<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use Faker\Provider\Fakecar;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Fakecar($this->faker));
        $vehicle = $this->faker->vehicleArray;
        return [
            'customer_id' => Customer::Factory(),
            'brand' => $vehicle['brand'],
            'model' => $vehicle['model'],
            'plate_number' => $this->faker->vehicleRegistration('[0-9]{5}-[A-D]{1}-[0-9]{2}'),
            'fuel_type' => $this->faker->vehicleFuelType()

        ];
    }
}
