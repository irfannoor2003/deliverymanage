<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            User::create([
                'name' => 'Hunzla Malik',
                'pin' => Hash::make('2403'),
                'role' => 'admin',
            ]);

            User::create([
                'name' => 'Awais Anwar',
                'pin' => Hash::make('1234'),
                'role' => 'saleshead',
            ]);

            User::create([
                'name' => 'Moeen Khalid',
                'pin' => Hash::make('0000'),
                'role' => 'deliveryboy',
            ]);

            User::create([
                'name' => 'Arif Hussain ',
                'pin' => Hash::make('1111'),
                'role' => 'deliveryboy',
            ]);
        }
    }
}
