<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()
        ->count(15)
        ->hasTransactions(5)
        ->hasOrders(4)
        ->create();

        Product::factory()
        ->count(20)
        ->hasTransactions(10)
        ->hasOrders(8)
        ->create();
    }
}
