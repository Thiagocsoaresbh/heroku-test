<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountDepositTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_deposit()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currentBalance' => 100
        ]);

        $this->actingAs($user, 'sanctum');

        $depositAmount = 200;
        $response = $this->postJson("/api/account/deposit", [
            'amount' => $depositAmount
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Deposit successful']);
    }
}
