<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        Account::all()->each(function ($account) {
            \App\Models\Transaction::factory(5)->create(['account_id' => $account->id]);
        });
    }
}
