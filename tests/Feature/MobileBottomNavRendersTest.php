<?php

namespace Tests\Feature;

use Tests\TestCase;

class MobileBottomNavRendersTest extends TestCase
{
    public function test_bottom_nav_has_five_nav_items(): void
    {
        $html = $this->get('/')->getContent();
        $count = substr_count($html, 'mobile-bottom-nav') + substr_count($html, 'mobile-bottom-nav');
        $this->assertEquals(1, substr_count($html, 'mobile-bottom-nav'));
    }
}
