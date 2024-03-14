<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountDepositTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test a successful account deposit.
     *
     * @return void
     */
    public function test_account_deposit_success()
    {
        // Create a new user for the test
        $user = User::factory()->create();

        $account = Account::factory()->create(['user_id' => $user->id]);
        $depositAmount = $this->faker->numberBetween(100, 500);

        // Act
        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->postJson("/api/accounts/{$account->id}/deposit", [
            'amount' => $depositAmount,
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Deposit successful',
            'balance' => $account->fresh()->currentBalance,
        ]);
        $this->assertEquals($account->fresh()->currentBalance, $account->currentBalance + $depositAmount);
    }
    /**
     * Test deposit with unauthenticated user.
     *
     * @return void
     */
    public function test_account_deposit_unauthenticated()
    {
        // Arrange
        $account = Account::factory()->create();
        $depositAmount = $this->faker->numberBetween(100, 500);

        // Act
        $response = $this->postJson("/api/accounts/{$account->id}/deposit", [
            'amount' => $depositAmount,
        ]);

        // Assert
        $response->assertStatus(401); // 401 Unauthorized
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
