<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class CheckSeeder extends Seeder
{
    public function run()
    {
        Account::all()->each(function ($account) {
            \App\Models\Check::factory(3)->create(['account_id' => $account->id]);
        });
    }
}
