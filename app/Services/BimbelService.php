<?php

namespace App\Services;

use App\Models\Mentor;
use App\Models\MentorBooking;

class BimbelService
{
    public function __construct(
        protected XenditService $xenditService,
    ) {}

    /**
     * Create a bimbel booking with a Xendit payment URL.
     *
     * @param  array<string, mixed>  $data
     */
    public function createBooking(Mentor $mentor, array $data): array
    {
        if (! $mentor->is_active) {
            throw new \Exception('Mentor tidak tersedia');
        }

        $booking = MentorBooking::create([
            'mentor_id' => $mentor->id,
            'client_name' => $data['client_name'],
            'client_email' => $data['client_email'],
            'client_whatsapp' => $data['client_whatsapp'],
            'amount' => $mentor->price,
            'status' => 'pending',
        ]);

        // external_id references the booking id, so set it right after creation
        $booking->update(['external_id' => 'mentor_booking_'.$booking->id]);

        // Create Xendit invoice
        $invoice = $this->xenditService->createMentorBookingInvoice($booking);

        // Update booking with Xendit info
        $booking->update([
            'xendit_id' => $invoice['id'],
            'payment_method' => $invoice['payment_method'] ?? null,
            'payment_channel' => $invoice['payment_channel'] ?? null,
            'invoice_url' => $invoice['invoice_url'] ?? null,
        ]);

        return [
            'booking' => $booking,
            'payment_url' => $invoice['invoice_url'] ?? url("/bimbel/pembayaran/{$booking->id}/simulasi"),
        ];
    }

    /**
     * Handle a Xendit-style callback payload for a mentor booking.
     *
     * @param  array<string, mixed>  $payload
     */
    public function handleCallback(array $payload): ?MentorBooking
    {
        $externalId = $payload['external_id'] ?? null;
        $xenditId = $payload['id'] ?? null;

        $booking = null;
        if ($externalId && str_starts_with($externalId, 'mentor_booking_')) {
            $bookingId = str_replace('mentor_booking_', '', $externalId);
            if (is_numeric($bookingId)) {
                $booking = MentorBooking::find((int) $bookingId);
            }
        }

        if (! $booking && $xenditId) {
            $booking = MentorBooking::where('xendit_id', $xenditId)->first();
        }

        if (! $booking) {
            return null;
        }

        // If already paid, return as-is
        if ($booking->status === 'paid') {
            return $booking;
        }

        $status = strtoupper((string) ($payload['status'] ?? ''));

        match ($status) {
            'PAID' => $booking->markAsPaid($xenditId),
            'EXPIRED' => $booking->markAsExpired(),
            'FAILED', 'CANCELLED' => $booking->markAsFailed(),
            default => null,
        };

        return $booking->fresh();
    }

    /**
     * Format amount for display.
     */
    public function formatAmount(int $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }
}
