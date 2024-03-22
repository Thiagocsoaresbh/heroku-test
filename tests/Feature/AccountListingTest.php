<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_their_account()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/api/account');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'accountNumber' => $account->accountNumber
        ]);
    }

    public function test_user_cannot_list_other_users_account()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->getJson('/api/account');

        $response->assertStatus(200);
        $response->assertJsonMissing([
            'accountNumber' => $account->accountNumber
        ]);
    }
}
