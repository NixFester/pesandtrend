<?php

namespace Tests\Feature;

use Tests\TestCase;

class SchoolsIndexRendersTest extends TestCase
{
    public function test_filter_form_uses_search_bar_and_school_card_grid(): void
    {
        $html = $this->get('/schools')->getContent();
        $this->assertStringContainsString('schools/index', $html);
        $this->assertStringContainsString('auto-grid-cards', $html);
    }

    public function test_mobile_bottom_nav_uses_safe_bottom_utility(): void
    {
        $html = $this->get('/schools')->getContent();
        $this->assertStringContainsString('safe-bottom', $html);
    }

    public function test_breadcrumb_renders(): void
    {
        $html = $this->get('/schools')->getContent();
        $this->assertStringContainsString('Breadcrumb', $html);
    }
}
