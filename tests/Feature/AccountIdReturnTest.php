<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class AccountIdReturnTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_login_returns_account_id()
    {
        // Cria um usuário e uma conta associada no banco de dados de teste
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        // Dados de login
        $loginData = [
            'email' => $user->email,
            'password' => 'password', // Garanta que a senha seja 'password' na sua factory ou use Hash::make() apropriadamente
        ];

        // Faz uma requisição de login
        $response = $this->postJson('/api/login', $loginData);

        // Verifica se a resposta contém o accountId
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => [
                    'id',
                    'username',
                    'email',
                ],
                'account_id', // Confirma que o accountId está sendo retornado
            ]);

        // Opcional: Confirma que o accountId na resposta corresponde ao correto
        $this->assertEquals($account->id, $response['account_id']);
    }
}
