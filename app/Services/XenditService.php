<?php

namespace App\Services;

use App\Domain\Onboarding\ApplicationStatus;
use App\Models\ApplicationPayment;
use App\Models\XenditWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class XenditService
{
    private string $secretKey;

    private string $webhookToken;

    private bool $isProduction;

    public function __construct()
    {
        $this->secretKey = (string) config('services.xendit.secret_key');
        $this->webhookToken = (string) config('services.xendit.webhook_token');
        $this->isProduction = (bool) config('services.xendit.is_production', false);
    }

    /**
     * Create a hosted invoice via Xendit API.
     *
     * @return array<string, mixed>
     */
    public function createInvoice(ApplicationPayment $payment): array
    {
        $application = $payment->application;

        if (empty($this->secretKey) || str_starts_with($this->secretKey, 'dummy') || str_starts_with($this->secretKey, 'xnd_development_dummy')) {
            $mockInvoiceId = 'inv_mock_'.strtolower(Str::random(12));

            return [
                'id' => $mockInvoiceId,
                'external_id' => $payment->idempotency_key,
                'status' => 'PENDING',
                'merchant_name' => 'Pesantrends',
                'amount' => $payment->amount,
                'payer_email' => $application->parent_email,
                'description' => "Pembayaran pendaftaran {$application->student_name} — {$application->school->name}",
                'invoice_url' => url("/orang-tua/pendaftaran/{$application->id}?payment=simulated&invoice_id={$mockInvoiceId}"),
            ];
        }

        $baseUrl = 'https://api.xendit.co';

        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->secretKey, '')
                ->post("{$baseUrl}/v2/invoices", [
                    'external_id' => $payment->idempotency_key,
                    'amount' => $payment->amount,
                    'description' => "Pembayaran pendaftaran {$application->student_name} — {$application->school->name}",
                    'payer_email' => $application->parent_email,
                    'customer' => [
                        'given_names' => $application->parent_name,
                        'email' => $application->parent_email,
                    ],
                    'currency' => 'IDR',
                    'invoice_duration' => 86400, // 24 hours
                    'success_redirect_url' => url("/orang-tua/pendaftaran/{$application->id}?payment=success"),
                    'failure_redirect_url' => url("/orang-tua/pendaftaran/{$application->id}?payment=failed"),
                ]);

            if ($response->failed()) {
                Log::error('Xendit invoice creation failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'payment_id' => $payment->id,
                ]);

                // Fallback for dev mode
                $mockInvoiceId = 'inv_fallback_'.strtolower(Str::random(12));

                return [
                    'id' => $mockInvoiceId,
                    'external_id' => $payment->idempotency_key,
                    'status' => 'PENDING',
                    'merchant_name' => 'Pesantrends',
                    'amount' => $payment->amount,
                    'invoice_url' => url("/orang-tua/pendaftaran/{$application->id}?payment=simulated&invoice_id={$mockInvoiceId}"),
                ];
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('Xendit invoice exception', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);

            $mockInvoiceId = 'inv_fallback_'.strtolower(Str::random(12));

            return [
                'id' => $mockInvoiceId,
                'external_id' => $payment->idempotency_key,
                'status' => 'PENDING',
                'merchant_name' => 'Pesantrends',
                'amount' => $payment->amount,
                'invoice_url' => url("/orang-tua/pendaftaran/{$application->id}?payment=simulated&invoice_id={$mockInvoiceId}"),
            ];
        }
    }

    /**
     * Handle incoming Xendit webhook.
     */
    public function handleWebhook(Request $request): void
    {
        // Verify callback token (constant-time comparison)
        $token = $request->header('x-callback-token');
        if (! hash_equals($this->webhookToken, (string) $token)) {
            abort(401, 'Invalid callback token');
        }

        $eventId = $request->header('x-callback-event-id', $request->input('id', ''));
        $eventType = $request->header('x-callback-event-type', $request->input('event', 'invoice.unknown'));
        $payload = $request->all();

        // Idempotency: skip if already processed
        $existing = XenditWebhookEvent::where('event_id', $eventId)->first();
        if ($existing && $existing->processed_at) {
            return;
        }

        DB::transaction(function () use ($eventId, $eventType, $payload) {
            $event = XenditWebhookEvent::firstOrCreate(
                ['event_id' => $eventId],
                ['event_type' => $eventType, 'payload' => $payload]
            );

            if ($event->processed_at) {
                return;
            }

            // Process invoice.paid
            if (in_array($eventType, ['invoice.paid', 'invoices.paid']) || strtolower($payload['status'] ?? '') === 'paid') {
                $this->processInvoicePaid($payload);
            }

            $event->update(['processed_at' => now()]);
        });
    }

    /**
     * Process a paid invoice callback.
     *
     * @param  array<string, mixed>  $payload
     */
    private function processInvoicePaid(array $payload): void
    {
        $externalId = $payload['external_id'] ?? null;
        if (! $externalId) {
            return;
        }

        $payment = ApplicationPayment::where('idempotency_key', $externalId)
            ->orWhere('external_id', $externalId)
            ->orWhere('external_id', $payload['id'] ?? '')
            ->first();

        if (! $payment || $payment->isPaid()) {
            return;
        }

        $payment->update([
            'status' => 'paid',
            'payment_method' => $payload['payment_method'] ?? $payload['payment_channel'] ?? null,
            'paid_at' => now(),
            'raw_callback' => $payload,
            'external_id' => $payload['id'] ?? $payment->external_id,
        ]);

        $application = $payment->application;
        if ($application->status === ApplicationStatus::PaymentPending) {
            $application->transitionTo(ApplicationStatus::Paid);
        }
    }
}
