<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name'     => 'Admin 1',
            'email'    => 'admin1@housing.com',
            'password' => Hash::make('password123'),
        ]);
        
        User::create([ 
            'fname' => 'Mohamed', 
            'lname' => 'Hassan',
            'email' => 'owner@owner.com',
            'password' => Hash::make('password123'),
            'phone' => '01010000004',
            'gender' => 'male',
            'profile_image' => null,
            'role' => 'owner',
            'status' => 'active', 
            'national_id' => '30404040404040',
            'national_id_image' => null,
            'email_verified_at' => now(),
         ]);

User::create([
           'fname' => 'Ahmed',
           'lname' => 'Mohamed',
           'email' => 'ahmed@student.com',
           'password' => Hash::make('password123'),
           'phone' => '01010000001',
           'gender' => 'male', 
           'profile_image' => null,
           'role' => 'student', 
           'status' => 'active',
           'national_id' => '30101010101010', 
           'national_id_image' => null,
           'email_verified_at' => now(),
        ]);
        
    }
}
