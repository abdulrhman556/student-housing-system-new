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
    }
}
