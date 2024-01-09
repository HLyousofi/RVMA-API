<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()
            ->count(15)
            ->hasInvoices(5)
            ->hasVehicles(1)
            ->create();

        Customer::factory()
            ->count(20)
            ->hasInvoices(10)
            ->hasVehicles(2)
            ->create();
    }
}
