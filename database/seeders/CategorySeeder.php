<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()
        ->count(10)
        ->hasProducts(5)
        ->create();

        Category::factory()
        ->count(20)
        ->hasProducts(10)
        ->create();
    }
}
