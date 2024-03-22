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
        // Creating user and account
        $user = User::factory()->create();
        $sourceAccount = Account::factory()->create(['user_id' => $user->id, 'currentBalance' => 500]);
        $destinationAccount = Account::factory()->create(['user_id' => $user->id, 'currentBalance' => 100]);

        // Adjusting the balance on origin account to ensure it has sufficient amount
        $sourceAccount->currentBalance += 1000; // Adjusting value to enable transfer
        $sourceAccount->save();

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->postJson("/api/account/transfer", [
            'fromAccountId' => $sourceAccount->id,
            'toAccountId' => $destinationAccount->id,
            'amount' => 200,
        ]);

        $response->assertStatus(201); // Adjusting this line to expect status 201
        $this->assertDatabaseHas('account', [
            'id' => $sourceAccount->id,
            'currentBalance' => $sourceAccount->currentBalance - 200, // Confirming the new balance on origin account
        ]);
        $this->assertDatabaseHas('account', [
            'id' => $destinationAccount->id,
            'currentBalance' => $destinationAccount->currentBalance + 200, // Confirming the new balance on destination account
        ]);
    }
}
