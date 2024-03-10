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
            'account_id' => \App\Models\Account::factory(),
            'type' => $this->faker->randomElement(['income', 'expense', 'deposit']),
            'amount' => $this->faker->randomFloat(2, 1, 5000),
            'description' => $this->faker->sentence,
            'transactionDate' => $this->faker->dateTimeThisYear(),
        ];
    }
}
