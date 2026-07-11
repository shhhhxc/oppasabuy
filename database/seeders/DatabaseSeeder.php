<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'full_name' => 'Administrator',
            'email' => 'admin@oppasabuy.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'is_verified' => 1,
            'verification_status' => 'approved',
        ]);
         $this->call([
            LifestyleCategorySeeder::class,
        ]);
    }
    
}