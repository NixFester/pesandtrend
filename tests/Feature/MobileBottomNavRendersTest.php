<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileBottomNavRendersTest extends TestCase
{
    use RefreshDatabase;
    public function test_bottom_nav_has_five_nav_items_in_order(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('mobile-bottom-nav', $html);
        $this->assertStringContainsString('Beranda', $html);
        $this->assertStringContainsString('Cari', $html);
        $this->assertStringContainsString('Bantu', $html);
        $this->assertStringContainsString('Bimbel', $html);
        $this->assertStringContainsString('Login', $html);

        // Check relative order of labels in bottom nav
        $bottomNavPos = strpos($html, 'id="mobile-bottom-nav"');
        $this->assertNotFalse($bottomNavPos);
        $bottomNavEndPos = strpos($html, '</nav>', $bottomNavPos);
        $bottomNavHtml = substr($html, $bottomNavPos, $bottomNavEndPos - $bottomNavPos + 6);

        $posBeranda = strpos($bottomNavHtml, 'Beranda');
        $posCari = strpos($bottomNavHtml, 'Cari');
        $posBantu = strpos($bottomNavHtml, 'Bantu');
        $posBimbel = strpos($bottomNavHtml, 'Bimbel');
        $posLogin = strpos($bottomNavHtml, 'Login');

        $this->assertTrue($posBeranda < $posCari);
        $this->assertTrue($posCari < $posBantu);
        $this->assertTrue($posBantu < $posBimbel);
        $this->assertTrue($posBimbel < $posLogin);
    }

    public function test_desktop_navbar_excludes_sekolah_terbaik_pesantren_terbaik_bandingkan(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Desktop nav cluster shouldn't have these direct links
        $html = $response->getContent();
        $desktopNavStart = strpos($html, 'hidden items-center gap-1 lg:flex');
        $this->assertNotFalse($desktopNavStart);
        $desktopNavHtml = substr($html, $desktopNavStart, 800);

        $this->assertStringNotContainsString('Sekolah Terbaik', $desktopNavHtml);
        $this->assertStringNotContainsString('Pesantren Terbaik', $desktopNavHtml);
        $this->assertStringNotContainsString('Bandingkan', $desktopNavHtml);
    }

    public function test_cari_sekolah_page_contains_quick_navigation_to_best_and_compare(): void
    {
        $response = $this->get(route('schools.index'));
        $response->assertStatus(200);

        $response->assertSee('Sekolah Terbaik');
        $response->assertSee('Pesantren Terbaik');
        $response->assertSee('Bandingkan Sekolah');
        $response->assertSee(route('seo.schools.best'));
        $response->assertSee(route('seo.pesantren.best'));
        $response->assertSee(route('compare.index'));
    }
}
