<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user with PIN: 1234
        User::create([
            'name' => 'Admin',
            'pin' => Hash::make('1234'),
            'role' => 'admin',
        ]);

        // Create Sales Head
        User::create([
            'name' => 'Sales Head',
            'pin' => Hash::make('5678'),
            'role' => 'saleshead',
        ]);

        // Create Delivery Boy
        User::create([
            'name' => 'John Delivery',
            'pin' => Hash::make('0000'),
            'role' => 'deliveryboy',
        ]);

        $this->call(DeliveryRouteSeeder::class);
    }
}
