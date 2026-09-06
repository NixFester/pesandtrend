<?php

namespace Tests\Feature;

use App\Domain\Onboarding\ApplicationStatus;
use Tests\TestCase;

class OnboardingStatusChipTest extends TestCase
{
    public function test_all_application_statuses_map_to_chip_classes(): void
    {
        foreach (ApplicationStatus::cases() as $status) {
            $color = $status->color();
            $this->assertContains($color, ['success', 'warning', 'danger', 'info']);
        }
    }
}
