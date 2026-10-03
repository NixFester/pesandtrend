<?php

namespace App\Services;

use App\Domain\Onboarding\ApplicationStatus;
use App\Models\ApplicationPayment;
use App\Models\Donation;
use App\Models\MentorBooking;
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
     * Create a hosted Invoice via Xendit API for a Donation.
     *
     * @return array<string, mixed>
     */
    public function createDonationInvoice(Donation $donation): array
    {
        $campaign = $donation->campaign;

        // Only use mock if key is truly empty or contains "dummy"
        $isTestMode = empty($this->secretKey) || str_contains($this->secretKey, 'dummy');

        if ($isTestMode) {
            $mockInvoiceId = 'inv_donation_'.strtolower(Str::random(12));

            return [
                'id' => $mockInvoiceId,
                'external_id' => 'donation_'.$donation->id,
                'status' => 'PENDING',
                'merchant_name' => 'Pesantrends',
                'amount' => $donation->amount,
                'payer_email' => $donation->donor_email,
                'description' => "Donasi untuk: {$campaign->title}",
                'invoice_url' => url("/bantu-pesantren/pembayaran/{$donation->id}/simulasi"),
            ];
        }

        $baseUrl = 'https://api.xendit.co';

        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->secretKey, '')
                ->post("{$baseUrl}/v2/invoices", [
                    'external_id' => 'donation_'.$donation->id,
                    'amount' => $donation->amount,
                    'description' => "Donasi untuk: {$campaign->title}",
                    'payer_email' => $donation->donor_email,
                    'customer' => [
                        'given_names' => $donation->donor_name,
                        'email' => $donation->donor_email,
                    ],
                    'currency' => 'IDR',
                    'invoice_duration' => 86400,
                    'success_redirect_url' => url("/bantu-pesantren/{$donation->id}/sukses"),
                    'failure_redirect_url' => url("/bantu-pesantren/{$donation->id}/gagal"),
                ]);

            if ($response->failed()) {
                Log::error('Xendit donation invoice creation failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'donation_id' => $donation->id,
                ]);

                throw new \Exception('Xendit API failed: '.$response->status());
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('Xendit donation invoice exception', [
                'error' => $e->getMessage(),
                'donation_id' => $donation->id,
            ]);

            throw $e;
        }
    }

    /**
     * Create a hosted Invoice via Xendit API for a Bimbel mentor booking.
     *
     * @return array<string, mixed>
     */
    public function createMentorBookingInvoice(MentorBooking $booking): array
    {
        $mentor = $booking->mentor;

        // Only use mock if key is truly empty or contains "dummy"
        $isTestMode = empty($this->secretKey) || str_contains($this->secretKey, 'dummy');

        $successUrl = url("/bimbel/{$mentor->slug}/sukses/{$booking->id}");
        $failureUrl = url("/bimbel/{$mentor->slug}/gagal/{$booking->id}");

        if ($isTestMode) {
            $mockInvoiceId = 'inv_bimbel_'.strtolower(Str::random(12));

            return [
                'id' => $mockInvoiceId,
                'external_id' => 'mentor_booking_'.$booking->id,
                'status' => 'PENDING',
                'merchant_name' => 'Pesantrends',
                'amount' => $booking->amount,
                'payer_email' => $booking->client_email,
                'description' => "Pembayaran Bimbel - {$mentor->name}",
                'invoice_url' => url("/bimbel/pembayaran/{$booking->id}/simulasi"),
            ];
        }

        $baseUrl = 'https://api.xendit.co';

        try {
            $response = Http::withoutVerifying()
                ->withBasicAuth($this->secretKey, '')
                ->post("{$baseUrl}/v2/invoices", [
                    'external_id' => 'mentor_booking_'.$booking->id,
                    'amount' => $booking->amount,
                    'description' => "Pembayaran Bimbel - {$mentor->name}",
                    'payer_email' => $booking->client_email,
                    'customer' => [
                        'given_names' => $booking->client_name,
                        'email' => $booking->client_email,
                    ],
                    'currency' => 'IDR',
                    'invoice_duration' => 86400,
                    'success_redirect_url' => $successUrl,
                    'failure_redirect_url' => $failureUrl,
                ]);

            if ($response->failed()) {
                Log::error('Xendit mentor booking invoice creation failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'booking_id' => $booking->id,
                ]);

                throw new \Exception('Xendit API failed: '.$response->status());
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('Xendit mentor booking invoice exception', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            throw $e;
        }
    }

    /**
     * Create a hosted invoice via Xendit API.
     *
     * @return array<string, mixed>
     */
    public function createInvoice(ApplicationPayment $payment): array
    {
        $application = $payment->application;

        // Only use mock if key is truly empty or contains "dummy"
        $isTestMode = empty($this->secretKey) || str_contains($this->secretKey, 'dummy');

        if ($isTestMode) {
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

        // Check if it's a donation (external_id starts with "donation_")
        if (str_starts_with($externalId, 'donation_')) {
            $this->processDonationPaid($payload);

            return;
        }

        // Check if it's a bimbel mentor booking (external_id starts with "mentor_booking_")
        if (str_starts_with($externalId, 'mentor_booking_')) {
            $this->processMentorBookingPaid($payload);

            return;
        }

        // Otherwise, process as application payment
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

    /**
     * Process a paid donation invoice callback.
     *
     * @param  array<string, mixed>  $payload
     */
    private function processDonationPaid(array $payload): void
    {
        $externalId = $payload['external_id'] ?? null;
        $donationId = $externalId ? str_replace('donation_', '', $externalId) : null;

        if (! $donationId || ! is_numeric($donationId)) {
            Log::warning('Invalid donation external_id', ['external_id' => $externalId]);

            return;
        }

        $donation = Donation::find((int) $donationId);
        if (! $donation || $donation->is_paid) {
            return;
        }

        $donation->markAsPaid($payload['id'] ?? null);
    }

    /**
     * Process a paid bimbel mentor booking invoice callback.
     *
     * @param  array<string, mixed>  $payload
     */
    private function processMentorBookingPaid(array $payload): void
    {
        $externalId = $payload['external_id'] ?? null;
        $bookingId = $externalId ? str_replace('mentor_booking_', '', $externalId) : null;

        if (! $bookingId || ! is_numeric($bookingId)) {
            Log::warning('Invalid mentor booking external_id', ['external_id' => $externalId]);

            return;
        }

        $booking = MentorBooking::find((int) $bookingId);
        if (! $booking || $booking->is_paid) {
            return;
        }

        $booking->markAsPaid($payload['id'] ?? null);
    }
}
