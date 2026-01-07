<?php

namespace Database\Seeders;

use App\Models\DeliveryMan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeliverymanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryMan::create([
            'f_name' => 'd_man',
            'l_name' => 'd_man',
            'phone' => '1234567890',
            'image' => 'adb.jpg',
            'email' => 'delivery@gmail.com',
            'password' => Hash::make('dk123123'),
            'remember_token' => '',
            // 'role_id' => null,
            // 'zone_id' => null,
        ]);
        
    }
}
