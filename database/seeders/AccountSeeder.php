<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::all()->each(function ($user) {
            // Assuming each user only has one account
            if (!$user->hasAccount()) {
                $user->account()->create(
                    \App\Models\Account::factory()->raw()
                );
            }
        });
    }
}
