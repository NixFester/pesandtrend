<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationPayment;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ProofPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_payment_proof_downloads_pdf(): void
    {
        $user = User::factory()->create();
        $school = School::factory()->create();

        $app = Application::create([
            'public_id' => 'APP-PROOF-001',
            'school_id' => $school->id,
            'parent_user_id' => $user->id,
            'created_by_user_id' => $user->id,
            'student_name' => 'Budi Santoso',
            'parent_name' => 'Bapak Budi',
            'status' => 'submitted',
        ]);

        $payment = ApplicationPayment::create([
            'application_id' => $app->id,
            'external_id' => 'INV-PROOF-001',
            'provider' => 'xendit',
            'amount' => 5750000,
            'status' => 'paid',
            'breakdown_json' => ['registration_fee' => 250000, 'spp_monthly' => 500000],
            'paid_at' => now(),
        ]);

        $signedUrl = URL::signedRoute('proof.print', ['payment' => $payment->id]);

        $response = $this->actingAs($user)->get($signedUrl);
        $response->assertOk();
    }
}
