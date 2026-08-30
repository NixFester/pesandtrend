<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsoleCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_make_admin_command_assigns_admin_role(): void
    {
        $user = User::factory()->create(['email' => 'testadmin@pesantrends.id', 'role' => 'parent']);

        $this->artisan('pesantrends:make-admin testadmin@pesantrends.id')
            ->expectsOutput('User ['.$user->name.'] is now an admin.')
            ->assertExitCode(0);

        $user->refresh();
        $this->assertEquals('admin', $user->role);
        $this->assertTrue($user->hasRole('admin'));
    }
}
