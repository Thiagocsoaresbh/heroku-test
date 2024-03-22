<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_created()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
    public function test_user_cannot_create_multiple_accounts()
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->plainTextToken;

        // Trying to create an account with the same account number
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/accounts', [
            'accountNumber' => '123456789',
            'currentBalance' => 0,
        ]);

        $response->assertStatus(422); // Another appropriate status code can be used
    }
}
