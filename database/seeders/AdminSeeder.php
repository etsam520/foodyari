<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::query()->delete(); // Clear existing records
        Admin::create([
            'f_name' => 'Admin',
            'l_name' => 'Admin',
            'phone' => '1234567890',
            'image' => 'adb.jpg',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('dk123123'), // make sure to use a secure password
            'role_id' => null,
            'zone_id' => null, // assuming you have a 'role' field in the users table
        ]);
    }
}
