<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_with_valid_data()
    {
        // Testa a criação de um usuário com dados válidos
        $response = $this->postJson('/api/register', [
            'username' => 'newuser22',
            'email' => 'newuser22@example.com',
            'password' => 'password1231',
            'role' => 'customer',
        ]);

        $response->assertStatus(201);

        // Corrige a assertiva para verificar o email do usuário no path correto do JSON retornado
        // Assumindo que o email do usuário está diretamente no nível superior do objeto user
        $response->assertJsonPath('user.email', 'newuser22@example.com');
    }

    public function test_user_registration_with_invalid_data()
    {
        // Testa a criação de um usuário com dados inválidos
        $response = $this->postJson('/api/register', [
            'username' => '',
            'email' => 'invalidemail',
            'password' => 'pass',
            'role' => 'invalidRole',
        ]);

        $response->assertStatus(422); // Espera-se um erro de validação
    }
}
