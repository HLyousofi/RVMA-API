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
            ->count(5)
            ->hasOrders(17)
            ->create();

        Invoice::factory()
            ->count(8)
            ->hasOrders(10)
            ->create();
    }
}
