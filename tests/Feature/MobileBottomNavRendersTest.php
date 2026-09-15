<?php

namespace Tests\Feature;

use Tests\TestCase;

class MobileBottomNavRendersTest extends TestCase
{
    public function test_bottom_nav_has_five_nav_items(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('mobile-bottom-nav', $html);
    }
}
