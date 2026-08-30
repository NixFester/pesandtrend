<?php

namespace Tests\Unit;

use App\Domain\Onboarding\ApplicationStatus;
use PHPUnit\Framework\TestCase;

class ApplicationStatusTest extends TestCase
{
    public function test_it_has_correct_labels_and_colors(): void
    {
        $this->assertEquals('Draf', ApplicationStatus::Draft->label());
        $this->assertEquals('gray', ApplicationStatus::Draft->color());

        $this->assertEquals('Diajukan', ApplicationStatus::Submitted->label());
        $this->assertEquals('info', ApplicationStatus::Submitted->color());

        $this->assertEquals('Terverifikasi', ApplicationStatus::Verified->label());
        $this->assertEquals('success', ApplicationStatus::Verified->color());

        $this->assertEquals('Ditolak', ApplicationStatus::Rejected->label());
        $this->assertEquals('danger', ApplicationStatus::Rejected->color());
    }

    public function test_valid_state_transitions(): void
    {
        $this->assertTrue(ApplicationStatus::Draft->canTransitionTo(ApplicationStatus::Submitted));
        $this->assertTrue(ApplicationStatus::Draft->canTransitionTo(ApplicationStatus::PaymentPending));
        $this->assertTrue(ApplicationStatus::Draft->canTransitionTo(ApplicationStatus::Cancelled));

        $this->assertTrue(ApplicationStatus::Submitted->canTransitionTo(ApplicationStatus::DocumentReview));
        $this->assertTrue(ApplicationStatus::Submitted->canTransitionTo(ApplicationStatus::Rejected));

        $this->assertTrue(ApplicationStatus::DocumentReview->canTransitionTo(ApplicationStatus::Verified));
        $this->assertTrue(ApplicationStatus::DocumentReview->canTransitionTo(ApplicationStatus::Rejected));

        $this->assertFalse(ApplicationStatus::Cancelled->canTransitionTo(ApplicationStatus::Submitted));
    }
}
