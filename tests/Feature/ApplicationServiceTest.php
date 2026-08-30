<?php

namespace Tests\Feature;

use App\Domain\Onboarding\ApplicationStatus;
use App\Models\School;
use App\Models\User;
use App\Services\ApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake([
            'https://api.xendit.co/*' => Http::response([
                'id' => 'inv_test_mock_123',
                'invoice_url' => 'https://checkout.xendit.co/web/inv_test_mock_123',
            ], 200),
        ]);
        $this->service = app(ApplicationService::class);
    }

    public function test_it_creates_application_draft_with_ulid_public_id(): void
    {
        $user = User::factory()->create(['role' => 'parent']);
        $school = School::factory()->create();

        $app = $this->service->createDraft([
            'school_id' => $school->id,
            'student_name' => 'Ahmad Santri',
            'parent_name' => 'Bapak Ahmad',
            'parent_email' => 'parent@example.com',
        ], $user);

        $this->assertNotNull($app->public_id);
        $this->assertEquals(ApplicationStatus::Draft, $app->status);
        $this->assertEquals('Ahmad Santri', $app->student_name);
        $this->assertEquals($user->id, $app->created_by_user_id);
        $this->assertEquals($user->id, $app->parent_user_id);
    }

    public function test_it_submits_draft_application(): void
    {
        $user = User::factory()->create(['role' => 'parent']);
        $school = School::factory()->create();

        $app = $this->service->createDraft([
            'school_id' => $school->id,
            'student_name' => 'Siti Nurhaliza',
            'parent_name' => 'Ibu Siti',
        ], $user);

        $submittedApp = $this->service->submit($app);

        $this->assertEquals(ApplicationStatus::Submitted, $submittedApp->status);
        $this->assertNotNull($submittedApp->submitted_at);
    }

    public function test_it_creates_payment_record_with_breakdown(): void
    {
        $user = User::factory()->create(['role' => 'parent']);
        $school = School::factory()->create([
            'uang_pangkal' => 5000000,
            'spp_monthly' => 500000,
        ]);

        $app = $this->service->createDraft([
            'school_id' => $school->id,
            'student_name' => 'Fatimah',
            'parent_name' => 'Bapak Fatimah',
        ], $user);

        $payment = $this->service->createPayment($app, $user);

        $expectedTotal = config('payments.registration_fee', 250000) + 5000000 + 500000;
        $this->assertEquals($expectedTotal, $payment->amount);
        $this->assertEquals('pending', $payment->status);
        $this->assertArrayHasKey('registration_fee', $payment->breakdown_json);
    }
}
