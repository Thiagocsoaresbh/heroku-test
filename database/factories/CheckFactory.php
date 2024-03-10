<?php

namespace Database\Factories;

use App\Models\Check;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckFactory extends Factory
{
    protected $model = Check::class;

    public function definition()
    {
        return [
            'account_id' => \App\Models\Account::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'description' => $this->faker->sentence,
            'imagePath' => 'checks/' . $this->faker->unique()->word . '.png',
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
            'submissionDate' => $this->faker->dateTimeThisYear(),
        ];
    }
}
