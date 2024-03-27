<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Check;

class CheckApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_check()
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $check = Check::factory()->create(['status' => 'pending']);

        $admin = User::factory()->create(['role' => 'administrator'])->first();
        $response = $this->actingAs($admin, 'sanctum')->postJson("/api/admin/checks/{$check->id}/approve");

        $response->assertStatus(200);
        $this->assertEquals('approved', $check->fresh()->status);
    }
}
