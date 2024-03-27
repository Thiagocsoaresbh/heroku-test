<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountWithdrawTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_withdraw_with_sufficient_funds()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currentBalance' => 1000,
        ]);

        $user = User::factory()->create()->first();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/account/withdraw', [
            'amount' => 500,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(500, $account->fresh()->currentBalance);
    }

    public function test_account_withdraw_with_insufficient_funds()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currentBalance' => 100,
        ]);

        $user = User::factory()->create()->first();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/account/withdraw', [
            'amount' => 500,
        ]);

        $response->assertStatus(403);
        $this->assertEquals(100, $account->fresh()->currentBalance);
    }
}
