<?php

namespace Database\Seeders;

use App\Models\customers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        {
            $users = [
                [
                    'name' => 'yoga firmansyah',
                    'no_hp' => '083894708414',
                    'point' => '100'
                ],
                [
                    'name' => 'muhamad rasya',
                    'no_hp' => '083894709185',
                    'point' => '100',
                ],
            ];
    
            foreach ($users as $user) {
                customers::create($user);
            }
        }
    }
}
