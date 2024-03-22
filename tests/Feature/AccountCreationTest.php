<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_account()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/account', [
            'accountNumber' => '123456789',
            'currentBalance' => 1000,
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'accountNumber' => '123456789',
            'currentBalance' => 1000,
        ]);
        $this->assertDatabaseHas('account', [
            'user_id' => $user->id,
            'accountNumber' => '123456789',
            'currentBalance' => 1000,
        ]);
    }
}
