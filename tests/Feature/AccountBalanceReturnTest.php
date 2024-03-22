<?php

// tests/Feature/AccountTest.php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountBalanceReturnTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_balance_for_a_given_account_id()
    {
        // Create a user and an associated account in the test database
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currentBalance' => 1000.00,
        ]);

        // Authenticate the user
        /** @var \App\Models\User $user */
        $this->actingAs($user, 'sanctum');

        // Make a request to get the account balance
        $response = $this->getJson("/api/account/{$account->id}/balance");

        // Verify that the response contains the current balance
        $response
            ->assertStatus(200)
            ->assertJson([
                'currentBalance' => $account->currentBalance,
            ]);
    }
}
