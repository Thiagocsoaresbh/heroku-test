<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_with_valid_data()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('user.email', 'newuser@example.com');
    }

    public function test_user_registration_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'username' => '',
            'email' => 'invalidemail',
            'password' => 'pass',
            'role' => 'invalidRole',
        ]);

        $response->assertStatus(422);
    }
}
