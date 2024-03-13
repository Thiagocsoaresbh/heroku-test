<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'account_id' => \App\Models\Account::factory(), // This will create a new account and return the id of the created account
            'type' => $this->faker->randomElement(['income', 'expense', 'deposit']),
            'amount' => $this->faker->numberBetween(100, 5000), // Generate a random number between 100 and 5000 now
            'description' => $this->faker->sentence,
            'transactionDate' => $this->faker->dateTimeThisYear(),
        ];
    }
}
