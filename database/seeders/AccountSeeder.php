<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run()
    {
        // Assuring that the 'accounts' table is empty
        \App\Models\User::all()->each(function ($user) {
            // using the 'create' method to create a new account for each user
            $user->accounts()->create(
                \App\Models\Account::factory()->raw() // 'raw' generate an array of attributes''
            );
        });
    }
}
