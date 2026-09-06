<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutAccessibilityTest extends TestCase
{
    public function test_skip_link_renders_on_home_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Lewati ke konten utama', false);
    }

    public function test_main_landmark_renders(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('main', false);
        $response->assertSee('id="main-content"', false);
    }

    public function test_theme_color_meta_present(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('theme-color', $html);
        $this->assertStringContainsString('#0b2e1c', $html);
    }

    public function test_safe_area_bottom_utility_not_used(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringNotContainsString('safe-area-bottom', $html);
    }

    public function test_mobile_bottom_nav_safe_bottom_padding(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('safe-bottom', $html);
        $this->assertStringNotContainsString('safe-area-bottom', $html);
        $this->assertStringContainsString('mobile-bottom-nav', $html);
    }
}
