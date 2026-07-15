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
            'name' => 'Hunzla Malik',
            'pin' => Hash::make('2403'),
            'role' => 'admin',
        ]);

        // Create Sales Head
        User::create([
            'name' => 'Awais Anwar',
            'pin' => Hash::make('0077'),
            'role' => 'saleshead',
        ]);

        // Create Delivery Boy
        User::create([
            'name' => 'Moeen Khalid',
            'pin' => Hash::make('0000'),
            'role' => 'deliveryboy',
        ]);
        User::create([
            'name' => 'Arif Hussain ',
            'pin' => Hash::make('2637'),
            'role' => 'deliveryboy',
        ]);

    }
}
