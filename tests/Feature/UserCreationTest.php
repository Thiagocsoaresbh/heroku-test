<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_create_multiple_accounts()
    {
        // First, create a new user account with a role of customer
        $response = $this->postJson('/api/register', [
            'username' => 'userTest',
            'email' => 'user@test.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertStatus(201);

        // Getting the token for the user
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'user@test.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse['access_token'];

        // Try to create a new account for the user
        $responseCreateAccount = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/account', [
            'accountNumber' => '1234567890',
            'currentBalance' => 1000,
        ]);

        // Verify that the response is successful and the account is created
        $responseCreateAccount->assertStatus(422);
    }
}
