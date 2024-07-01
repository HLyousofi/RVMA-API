<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;


class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::factory()
            ->count(3)
            ->hasOrders(10)
            ->create();

        Invoice::factory()
            ->count(4)
            ->hasOrders(10)
            ->create();
    }
}
