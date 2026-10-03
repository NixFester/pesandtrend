<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;

class DonationService
{
    public function __construct(
        protected XenditService $xenditService,
    ) {}

    /**
     * Create a donation with Xendit payment URL.
     */
    public function createDonation(array $data): array
    {
        $campaign = Campaign::findOrFail($data['campaign_id']);

        // Validate campaign is active
        if ($campaign->status !== 'active') {
            throw new \Exception('Campaign is not active');
        }

        // Create donation record
        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => auth()->id(),
            'donor_name' => $data['donor_name'],
            'donor_email' => $data['donor_email'],
            'donor_phone' => $data['donor_phone'] ?? null,
            'donor_message' => $data['donor_message'] ?? null,
            'amount' => $data['amount'],
            'status' => 'pending',
        ]);

        // Create Xendit invoice
        $invoice = $this->xenditService->createDonationInvoice($donation);

        // Update donation with Xendit info
        $donation->update([
            'xendit_id' => $invoice['id'],
            'payment_method' => $invoice['payment_method'] ?? null,
            'payment_channel' => $invoice['payment_channel'] ?? null,
            'invoice_url' => $invoice['invoice_url'] ?? null,
        ]);

        return [
            'donation' => $donation,
            'payment_url' => $invoice['invoice_url'] ?? url("/bantu-pesantren/pembayaran/{$donation->id}/simulasi"),
        ];
    }

    /**
     * Handle Xendit webhook callback.
     */
    public function handleXenditCallback(array $payload): ?Donation
    {
        $xenditId = $payload['id'] ?? null;

        if (! $xenditId) {
            return null;
        }

        $donation = Donation::where('xendit_id', $xenditId)->first();

        if (! $donation) {
            return null;
        }

        // If already paid, return as-is
        if ($donation->status === 'paid') {
            return $donation;
        }

        $status = $payload['status'] ?? null;

        match ($status) {
            'PAID' => $donation->markAsPaid($xenditId),
            'EXPIRED', 'FAILED' => $donation->markAsFailed(),
            'REFUNDED' => $donation->markAsRefunded(),
            default => null,
        };

        return $donation->fresh();
    }

    /**
     * Get donation statistics.
     */
    public function getStats(): array
    {
        return [
            'total_donations' => Donation::paid()->count(),
            'total_amount' => Donation::paid()->sum('amount'),
            'total_campaigns' => Campaign::active()->count(),
            'recent_donations' => Donation::paid()
                ->with('campaign')
                ->latest('paid_at')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Format amount for display.
     */
    public function formatAmount(int $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }

    /**
     * Preset donation amounts.
     */
    public function presetAmounts(): array
    {
        return [
            50000,
            100000,
            250000,
            500000,
            1000000,
        ];
    }
}
