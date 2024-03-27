<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Check;
use App\Models\Account;

class CheckSubmissionTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_submit_check()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        $user = User::factory()->create()->first();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/checks', [
            'account_id' => $account->id,
            'amount' => 200,
            'description' => 'Test Check',
            // Assume you're sending a mock image or some identifier for the check image
            'image' => 'test_check_image.png',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('checks', [
            'account_id' => $account->id,
            'amount' => 200,
            'description' => 'Test Check',
            // Ensure the check image or its identifier is stored correctly
            'image' => 'test_check_image.png',
            'status' => 'pending', // Checks should be initially set as pending
        ]);
    }
}
