<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountOperationsTest extends TestCase
{
    use RefreshDatabase; // Use este trait para aplicar migrations ao banco de teste

    public function test_account_deposit_direct_to_account()
    {
        // Criação do usuário e da conta devem funcionar conforme esperado agora
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currentBalance' => 100
        ]);

        /** @var \App\Models\User $user */
        $this->actingAs($user, 'sanctum');

        $depositAmount = 200;
        $response = $this->postJson("/api/account/deposit", [
            'amount' => $depositAmount
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Deposit successful']);

        $updatedAccount = $account->fresh();
        $this->assertEquals(300, $updatedAccount->currentBalance);
    }
}
