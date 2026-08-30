<?php

namespace App\Domain\Onboarding;

enum ApplicationStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case DocumentReview = 'document_review';
    case Verified = 'verified';
    case Rejected = 'rejected';
    case PaymentPending = 'payment_pending';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    /**
     * Check if a transition to the given status is allowed.
     */
    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }

    /**
     * @return array<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft => [self::Submitted, self::PaymentPending, self::Cancelled],
            self::Submitted => [self::DocumentReview, self::PaymentPending, self::Rejected, self::Cancelled],
            self::DocumentReview => [self::Verified, self::Rejected],
            self::Verified => [self::PaymentPending, self::Cancelled],
            self::Rejected => [self::Draft],
            self::PaymentPending => [self::Paid, self::Cancelled],
            self::Paid => [self::Completed],
            self::Cancelled => [],
            self::Completed => [],
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Submitted => 'Diajukan',
            self::DocumentReview => 'Review Dokumen',
            self::Verified => 'Terverifikasi',
            self::Rejected => 'Ditolak',
            self::PaymentPending => 'Menunggu Pembayaran',
            self::Paid => 'Terbayar',
            self::Cancelled => 'Dibatalkan',
            self::Completed => 'Selesai',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Submitted => 'info',
            self::DocumentReview => 'warning',
            self::Verified => 'success',
            self::Rejected => 'danger',
            self::PaymentPending => 'warning',
            self::Paid => 'success',
            self::Cancelled => 'gray',
            self::Completed => 'success',
        };
    }
}
