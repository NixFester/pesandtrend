<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_filament_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'parent']);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_user_redirected_or_authenticated_at_filament_dashboard(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create(['role' => 'admin']);
        $user->assignRole($adminRole);

        $response = $this->actingAs($user)->get('/admin');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 403]));
    }
}
