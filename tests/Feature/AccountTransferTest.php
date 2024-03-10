<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class AccountTransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_transfer_money_to_another_account()
    {
        /** @var \App\Models\User $user */

        $user = User::factory()->create();
        $sourceAccount = Account::factory()->create(['user_id' => $user->id, 'currentBalance' => 500]);
        $destinationAccount = Account::factory()->create(['user_id' => $user->id, 'currentBalance' => 100]);

        $response = $this->actingAs($user)->postJson("/api/accounts/transfer", [
            'fromAccountId' => $sourceAccount->id,
            'toAccountId' => $destinationAccount->id,
            'amount' => 200,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('accounts', [
            'id' => $sourceAccount->id,
            'currentBalance' => 300, //Source account balance after transfer
        ]);
        $this->assertDatabaseHas('accounts', [
            'id' => $destinationAccount->id,
            'currentBalance' => 300, // Destination account balance after receiving transfer
        ]);
    }
}
