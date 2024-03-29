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
            'type' => $this->faker->randomElement(['income', 'expense', 'deposit']),
            'amount' => $this->faker->numberBetween(100, 5000),
            'description' => $this->faker->sentence,
            'transactionDate' => $this->faker->dateTimeThisYear(),
        ];
    }
}
