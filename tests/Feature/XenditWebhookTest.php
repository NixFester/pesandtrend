<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationPayment;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XenditWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_webhook_with_invalid_token(): void
    {
        config(['services.xendit.webhook_token' => 'secret_token_123']);

        $response = $this->postJson('/webhooks/xendit', [
            'id' => 'evt_123',
            'external_id' => 'INV-123',
            'status' => 'PAID',
        ], [
            'x-callback-token' => 'wrong_token',
        ]);

        $response->assertStatus(401);
    }

    public function test_it_processes_valid_webhook_and_updates_payment(): void
    {
        config(['services.xendit.webhook_token' => 'secret_token_123']);

        $user = User::factory()->create();
        $school = School::factory()->create();
        $app = Application::create([
            'public_id' => 'APP-01HQ123',
            'school_id' => $school->id,
            'parent_user_id' => $user->id,
            'created_by_user_id' => $user->id,
            'student_name' => 'Budi',
            'parent_name' => 'Bapak Budi',
            'status' => 'submitted',
        ]);

        $payment = ApplicationPayment::create([
            'application_id' => $app->id,
            'external_id' => 'INV-TEST-001',
            'provider' => 'xendit',
            'amount' => 5750000,
            'status' => 'pending',
            'breakdown_json' => ['registration_fee' => 250000],
        ]);

        $response = $this->postJson('/webhooks/xendit', [
            'id' => 'evt_test_999',
            'external_id' => 'INV-TEST-001',
            'status' => 'PAID',
            'paid_amount' => 5750000,
            'payment_method' => 'BANK_TRANSFER',
        ], [
            'x-callback-token' => 'secret_token_123',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('application_payments', [
            'id' => $payment->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('xendit_webhook_events', [
            'event_id' => 'evt_test_999',
        ]);
    }
}
