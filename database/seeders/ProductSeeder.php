<?php

namespace Database\Seeders;

use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $users = [
                [
                    'name' => 'Al Quran',
                    'price' => '45000',
                    'stock' => '10',
                    'image' => 'image'
                ],
                [
                    'name' => 'Juz Amma',
                    'price' => '20000',
                    'stock' => '10',
                    'image' => 'image'
                ],
            ];
    
            foreach ($users as $user) {
                products::create($user);
            }
        }
    }
}
