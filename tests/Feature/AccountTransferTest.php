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
        $user = User::factory()->create();
        $sourceAccount = Account::factory()->create(['user_id' => $user->id, 'currentBalance' => 500]);
        $destinationAccount = Account::factory()->create(['user_id' => $user->id, 'currentBalance' => 100]);

        $sourceAccount->increment('currentBalance', 1000); // Melhorando a lógica para adicionar saldo
        $amount = 200;

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->postJson("/api/account/transfer", [
            'fromAccountId' => $sourceAccount->id,
            'toAccountId' => $destinationAccount->id,
            'amount' => $amount,
        ]);

        $response->assertStatus(201);

        // Usando fresh() para obter o saldo atualizado diretamente do banco de dados
        $this->assertEquals(1300 - $amount, $sourceAccount->fresh()->currentBalance);
        $this->assertEquals(100 + $amount, $destinationAccount->fresh()->currentBalance);
    }
}
