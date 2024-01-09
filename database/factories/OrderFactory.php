<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vehicle;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Customer;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'invoice_id' => Invoice::factory(),
            'customer_id' => Customer::factory(),
            'quote_id' => Quote::factory(),
            'name' => $this->faker->text(8),
            'description' => $this->faker->text(20),
            'price' => $this->faker->numberBetween(100,20000),
            'status' => $this->faker->randomElement(['P', 'D', 'T', 'C'])
            

        ];
    }
}
