<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Verify if there are accounts in the database
        if (Account::count() == 0) {
            \App\Models\Account::factory(10)->create()->each(function ($account) {
                Transaction::factory(5)->create(['account_id' => $account->id]);
            });
        } else {
            Account::all()->each(function ($account) {
                Transaction::factory(5)->create(['account_id' => $account->id]);
            });
        }
    }
}
