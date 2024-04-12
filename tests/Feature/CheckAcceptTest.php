<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Check;

class CheckAcceptTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_accept_check()
    {
        // Creating an admin user
        $admin = User::factory()->create(['role' => 'administrator']);

        // Creating a pending check
        $check = Check::factory()->create(['status' => 'pending']);
        $this->assertModelExists($check);

        /** @var \App\Models\User $admin */
        $response = $this->actingAs($admin, 'sanctum')->postJson("/api/admin/checks/{check}/accept");

        // Verifying if the response status is 200
        $response->assertStatus(200);

        // Verifying if the check status has been updated to accepted
        $this->assertEquals('accepted', $check->fresh()->status);
    }
}
