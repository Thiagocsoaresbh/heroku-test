<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Check;

class CheckRejectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_reject_check()
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        $check = Check::factory()->create(['status' => 'pending']);

        $admin = User::factory()->create(['role' => 'administrator'])->first();
        $response = $this->actingAs($admin, 'sanctum')->postJson("/api/admin/checks/{$check->id}/reject");

        $response->assertStatus(200);
        $this->assertEquals('rejected', $check->fresh()->status);
    }
}
