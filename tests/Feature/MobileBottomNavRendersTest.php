<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileBottomNavRendersTest extends TestCase
{
    use RefreshDatabase;

    public function test_bottom_nav_has_four_requested_items_in_order(): void
    {
        $html = $this->get('/')->getContent();

        $bottomNavPos = strpos($html, 'id="mobile-bottom-nav"');
        $this->assertNotFalse($bottomNavPos);
        $bottomNavEndPos = strpos($html, '</nav>', $bottomNavPos);
        $bottomNavHtml = substr($html, $bottomNavPos, $bottomNavEndPos - $bottomNavPos + 6);

        preg_match_all('/<a\b/', $bottomNavHtml, $links);

        $this->assertSame(4, count($links[0]));
        $this->assertStringContainsString('href="'.route('home').'"', $bottomNavHtml);
        $this->assertStringContainsString('href="'.route('schools.index').'"', $bottomNavHtml);
        $this->assertStringContainsString('href="'.route('articles.index').'"', $bottomNavHtml);
        $this->assertStringContainsString('href="'.route('login').'"', $bottomNavHtml);

        $posBeranda = strpos($bottomNavHtml, 'Beranda');
        $posCari = strpos($bottomNavHtml, 'Cari');
        $posArtikel = strpos($bottomNavHtml, 'Artikel');
        $posLogin = strpos($bottomNavHtml, 'Login');

        $this->assertNotFalse($posBeranda);
        $this->assertNotFalse($posCari);
        $this->assertNotFalse($posArtikel);
        $this->assertNotFalse($posLogin);
        $this->assertTrue($posBeranda < $posCari);
        $this->assertTrue($posCari < $posArtikel);
        $this->assertTrue($posArtikel < $posLogin);
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
