<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carBrands = [
            'Toyota', 'Ford', 'Chevrolet', 'Honda', 'Mercedes-Benz', 'BMW', 
            'Volkswagen', 'Audi', 'Hyundai', 'Nissan', 'Kia', 'Porsche', 
            'Lexus', 'Subaru', 'Mazda', 'Jeep', 'Tesla', 'Volvo', 'Jaguar', 
            'Land Rover', 'Ferrari', 'Lamborghini', 'Mitsubishi', 'Peugeot', 
            'Fiat', 'Renault', 'Alfa Romeo', 'Acura', 'Infiniti', 
            'Cadillac', 'Buick', 'Dodge', 'Chrysler', 'GMC', 'Mini', 'Bentley', 
            'Rolls-Royce', 'Aston Martin', 'Maserati', 'McLaren', 'Bugatti', 
            'Pagani', 'Koenigsegg', 'Genesis', 'Tata', 'Mahindra', 'Holden', 
            'Saab', 'Citroën', 'SEAT', 'Škoda', 'Dacia', 'Opel', 'Vauxhall', 
            'Scania', 'Isuzu', 'Hino', 'Foton', 'Geely', 'Changan', 'BYD', 
            'Great Wall', 'Rivian', 'Lucid', 'Polestar', 'Proton', 'Perodua', 
            'SsangYong', 'MG', 'Rover', 'Smart', 'Fisker', 'Maybach', 'Daewoo', 
            'Eagle', 'Plymouth', 'Oldsmobile', 'Pontiac', 'Saturn', 'Mercury', 
            'Hummer', 'Studebaker', 'Packard', 'Delorean', 'Abarth', 'Lancia', 
            'Zastava', 'Yugo', 'Daihatsu', 'Suzuki'
        ];

        // Insert the car brands into the database
        foreach ($carBrands as $brand) {
            DB::table('car_brands')->insert([
                'label' => $brand,
            ]);
        }
    }
}
