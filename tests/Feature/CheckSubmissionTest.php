<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class CheckSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_check()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/checks', [
            'account_id' => $account->id,
            'amount' => 1220.00,
            'description' => 'Client Payment2',
            'imagePath' => 'path/to/image.jpg',
            'status' => 'pending',
            'submissionDate' => '2024-02-01',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('checks', [
            'account_id' => $account->id,
            'amount' => 1220.00,
            'description' => 'Client Payment2',
            'imagePath' => 'path/to/image.jpg',
            'status' => 'pending',
            'submissionDate' => '2024-02-01',
        ]);
    }
}
