<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'administrator',
        ]); // Adding a default admin user
        \App\Models\User::factory()->create([
            'username' => 'customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
        ]); //Adding a default customer user
        \App\Models\User::factory(10)->create();
    }
}
