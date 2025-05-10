<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Hamza',
            'last_name' => 'LYOUSOFI',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Toujours hasher les mots de passe
            'phone_number' => '0667121810',
        ]);
    }
}
