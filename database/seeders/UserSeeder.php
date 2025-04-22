<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $users = [
                [
                    'name' => 'admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('123'),
                    'role' => 'admin'
                ],
                [
                    'name' => 'user',
                    'email' => 'petugas@gmail.com',
                    'password' => Hash::make('123'),
                    'role' => 'employee'
                ],
            ];

                foreach ($users as $user) {
                    User::create($user);
                }
        }
    }
}
