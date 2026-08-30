<?php

namespace App\Services;

use App\Domain\Onboarding\ApplicationStatus;
use App\Models\Application;
use App\Models\ApplicationPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationService
{
    public function __construct(
        private XenditService $xenditService,
    ) {}

    /**
     * Create a draft application.
     *
     * @param  array<string, mixed>  $data
     */
    public function createDraft(array $data, ?User $user = null): Application
    {
        return DB::transaction(function () use ($data, $user) {
            $data['status'] = ApplicationStatus::Draft;
            $data['public_id'] = (string) Str::ulid();

            if ($user) {
                $data['created_by_user_id'] = $user->id;
                if ($user->role === 'parent' || ! $user->isAdmin()) {
                    $data['parent_user_id'] = $user->id;
                }
            }

            // Hash NIK for uniqueness index
            if (! empty($data['student_nik'])) {
                $data['student_nik_encrypted'] = $data['student_nik'];
                $data['student_nik_hash'] = hash_hmac('sha256', $data['student_nik'], config('app.key'));
                unset($data['student_nik']);
            }

            return Application::create($data);
        });
    }

    /**
     * Update application biodata.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Application $application, array $data): Application
    {
        return DB::transaction(function () use ($application, $data) {
            if (! empty($data['student_nik'])) {
                $data['student_nik_encrypted'] = $data['student_nik'];
                $data['student_nik_hash'] = hash_hmac('sha256', $data['student_nik'], config('app.key'));
                unset($data['student_nik']);
            }

            $application->update($data);

            return $application->fresh();
        });
    }

    /**
     * Submit the application for review.
     */
    public function submit(Application $application): Application
    {
        return DB::transaction(function () use ($application) {
            $application->transitionTo(ApplicationStatus::Submitted);
            $application->update(['submitted_at' => now()]);

            return $application;
        });
    }

    /**
     * Verify the application (admin action).
     */
    public function verify(Application $application, User $admin): Application
    {
        return DB::transaction(function () use ($application, $admin) {
            $application->transitionTo(ApplicationStatus::Verified);
            $application->update([
                'verified_by_user_id' => $admin->id,
                'verified_at' => now(),
            ]);

            return $application;
        });
    }

    /**
     * Reject the application with a reason.
     */
    public function reject(Application $application, string $reason): Application
    {
        return DB::transaction(function () use ($application, $reason) {
            $application->transitionTo(ApplicationStatus::Rejected);
            $application->update(['rejection_reason' => $reason]);

            return $application;
        });
    }

    /**
     * Create a Xendit payment for the application.
     */
    public function createPayment(Application $application, ?User $user = null): ApplicationPayment
    {
        return DB::transaction(function () use ($application, $user) {
            $school = $application->school;
            $registrationFee = (int) config('payments.registration_fee', 250000);

            $breakdown = [
                'registration_fee' => $registrationFee,
                'uang_pangkal' => (int) $school->uang_pangkal,
                'spp_first_month' => (int) $school->spp_monthly,
            ];

            $amount = array_sum($breakdown);
            $idempotencyKey = "app-{$application->public_id}-".Str::random(8);

            $payment = ApplicationPayment::create([
                'application_id' => $application->id,
                'provider' => 'xendit',
                'idempotency_key' => $idempotencyKey,
                'amount' => $amount,
                'breakdown_json' => $breakdown,
                'status' => 'pending',
                'recorded_by_user_id' => $user?->id,
            ]);

            // Create Xendit invoice
            $invoiceData = $this->xenditService->createInvoice($payment);
            $payment->update([
                'external_id' => $invoiceData['id'] ?? $invoiceData['external_id'] ?? null,
                'invoice_url' => $invoiceData['invoice_url'] ?? null,
            ]);

            // Transition application status
            $application->transitionTo(ApplicationStatus::PaymentPending);

            return $payment->fresh();
        });
    }

    /**
     * Record a manual (cash/transfer) payment.
     */
    public function markPaid(Application $application, User $admin, ?string $method = 'manual'): ApplicationPayment
    {
        return DB::transaction(function () use ($application, $admin, $method) {
            $school = $application->school;
            $registrationFee = (int) config('payments.registration_fee', 250000);

            $breakdown = [
                'registration_fee' => $registrationFee,
                'uang_pangkal' => (int) $school->uang_pangkal,
                'spp_first_month' => (int) $school->spp_monthly,
            ];

            $payment = ApplicationPayment::create([
                'application_id' => $application->id,
                'provider' => 'manual',
                'idempotency_key' => "manual-{$application->public_id}-".Str::random(8),
                'amount' => array_sum($breakdown),
                'breakdown_json' => $breakdown,
                'status' => 'paid',
                'payment_method' => $method,
                'paid_at' => now(),
                'recorded_by_user_id' => $admin->id,
            ]);

            if (! in_array($application->status, [ApplicationStatus::Paid, ApplicationStatus::Completed])) {
                if ($application->status === ApplicationStatus::PaymentPending) {
                    $application->transitionTo(ApplicationStatus::Paid);
                } else {
                    // Direct path for admin: verified → paid
                    $application->update(['status' => ApplicationStatus::Paid]);
                }
            }

            return $payment;
        });
    }

    /**
     * Mark application as completed.
     */
    public function markCompleted(Application $application): Application
    {
        return DB::transaction(function () use ($application) {
            $application->transitionTo(ApplicationStatus::Completed);

            return $application;
        });
    }
}
