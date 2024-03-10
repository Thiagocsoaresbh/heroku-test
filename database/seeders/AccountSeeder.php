<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::all()->each(function ($user) {
            $user->accounts()->save(\App\Models\Account::factory()->make());
        });
    }
}
