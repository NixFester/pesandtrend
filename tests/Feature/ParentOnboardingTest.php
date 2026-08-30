<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_onboarding(): void
    {
        $response = $this->get('/orang-tua/pendaftaran');
        $response->assertRedirect('/masuk');
    }

    public function test_parent_can_view_their_applications(): void
    {
        $user = User::factory()->create(['role' => 'parent']);
        $school = School::factory()->create();

        $app = Application::create([
            'public_id' => 'APP-01HQ999',
            'school_id' => $school->id,
            'parent_user_id' => $user->id,
            'created_by_user_id' => $user->id,
            'student_name' => 'Ananda Budi',
            'parent_name' => 'Bapak Budi',
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($user)->get('/orang-tua/pendaftaran');
        $response->assertOk();
        $response->assertSee('Ananda Budi');
    }

    public function test_parent_cannot_view_another_users_application(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $school = School::factory()->create();

        $app = Application::create([
            'public_id' => 'APP-SECRET-001',
            'school_id' => $school->id,
            'parent_user_id' => $user1->id,
            'created_by_user_id' => $user1->id,
            'student_name' => 'Rahasia 1',
            'parent_name' => 'Bapak Rahasia',
            'status' => 'submitted',
        ]);

        $response = $this->actingAs($user2)->get('/orang-tua/pendaftaran/'.$app->id);
        $response->assertStatus(403);
    }
}
