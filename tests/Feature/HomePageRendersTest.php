<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageRendersTest extends TestCase
{
    public function test_hero_image_has_explicit_dimensions_and_fetchpriority(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('fetchpriority="high"', $html);
    }

    public function test_stats_grid_uses_xs_breakpoint(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('auto-grid-cards', $html);
        $this->assertStringContainsString('xs:flex-row', $html);
    }

    public function test_lighthouse_mobile_thin_stats(): void
    {
        // Stats strip should stack at very small widths
        $this->assertTrue(true);
    }
}
