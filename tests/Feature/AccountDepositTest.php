<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountDepositTest extends TestCase
{
    use RefreshDatabase;

    public function testAccount_balance_is_updated_after_deposit()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id, 'currentBalance' => 100]);

        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $depositAmount = 200;
        $response = $this->postJson("/api/account/{$account->id}/deposit", ['amount' => $depositAmount]);

        $response->assertOk();

        $account->refresh(); // Recarrega o modelo do banco de dados

        $this->assertEquals(300, $account->currentBalance, "The account balance should be correctly updated after deposit.");
    }

    public function test_account_deposit()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/accounts/deposit', [
            'amount' => 100.00,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'currentBalance' => 100.00,
        ]);
    }
}
