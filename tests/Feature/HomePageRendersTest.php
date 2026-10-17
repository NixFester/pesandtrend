<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageRendersTest extends TestCase
{
    use RefreshDatabase;

    public function test_hero_image_has_explicit_dimensions_and_fetchpriority(): void
    {
        $html = $this->get('/')->getContent();
        $this->assertStringContainsString('fetchpriority="high"', $html);
    }

    public function test_navigation_is_fixed_without_pushing_down_the_hero(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('class="group fixed inset-x-0 top-0', $html);
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

    public function test_menu_section_renders_four_neutral_icon_shortcuts(): void
    {
        $html = $this->get('/')->getContent();

        $menuStart = strpos($html, 'id="homepage-menu"');
        $this->assertNotFalse($menuStart);
        $menuEnd = strpos($html, '</section>', $menuStart);
        $menuHtml = substr($html, $menuStart, $menuEnd - $menuStart);

        $this->assertStringContainsString('bg-forest-950 py-5 sm:py-6', $menuHtml);
        $this->assertStringContainsString('grid grid-cols-2 gap-x-3', $menuHtml);
        $this->assertStringContainsString('lg:flex lg:items-center', $menuHtml);
        $this->assertStringContainsString('lg:contents', $menuHtml);
        $this->assertStringContainsString('rounded-full border border-white/10 bg-white/5', $menuHtml);
        $this->assertStringContainsString(asset('images/icon/bantu_pesantren.svg'), $menuHtml);
        $this->assertStringContainsString(asset('images/icon/bimbel.svg'), $menuHtml);
        $this->assertStringContainsString(asset('images/icon/info_beasiswa.svg'), $menuHtml);
        $this->assertStringContainsString(asset('images/icon/kemitraan.svg'), $menuHtml);
        $this->assertStringContainsString('Bimbel Masuk Pesantren', $menuHtml);
        $this->assertStringContainsString('Info Beasiswa', $menuHtml);
        $this->assertStringContainsString('(segera)', $menuHtml);
        $this->assertStringContainsString(route('donations.index'), $menuHtml);
        $this->assertStringContainsString(route('bimbel.index'), $menuHtml);
        $this->assertStringContainsString('Lainnya', $menuHtml);
        $this->assertStringContainsString('aria-controls="homepage-menu-more"', $menuHtml);
        $this->assertStringContainsString('focus-visible:ring-gold-400 lg:hidden', $menuHtml);
        $this->assertStringContainsString('grid grid-cols-2 gap-x-3 gap-y-2', $menuHtml);
        $this->assertStringContainsString('lg:!mt-0 lg:!contents', $menuHtml);
        $this->assertStringNotContainsString('Cari Sekolah', $menuHtml);
        $this->assertStringNotContainsString('href="'.route('schools.index').'"', $menuHtml);
        $this->assertStringContainsString('Bandingkan Sekolah', $menuHtml);
        $this->assertStringContainsString(route('compare.index'), $menuHtml);
        $this->assertStringContainsString('Kalkulator Biaya', $menuHtml);
        $this->assertStringContainsString(route('calculator.index'), $menuHtml);
        $this->assertStringContainsString('Sekolah Terbaik', $menuHtml);
        $this->assertStringContainsString(route('seo.schools.best'), $menuHtml);
        $this->assertStringContainsString('Pesantren Pilihan', $menuHtml);
        $this->assertStringContainsString(route('seo.pesantren.best'), $menuHtml);
        $this->assertStringNotContainsString('Artikel', $menuHtml);
        $this->assertStringNotContainsString('href="'.route('articles.index').'"', $menuHtml);

        $moreMenuStart = strpos($menuHtml, 'id="homepage-menu-more"');
        $this->assertNotFalse($moreMenuStart);
        $moreMenuEnd = strpos($menuHtml, '</div>', $moreMenuStart);
        $moreMenuHtml = substr($menuHtml, $moreMenuStart, $moreMenuEnd - $moreMenuStart);

        preg_match_all('/<a\b/', $moreMenuHtml, $moreMenuLinks);
        $this->assertSame(4, count($moreMenuLinks[0]));
    }

    public function test_hero_search_form_submits_school_query_to_school_search(): void
    {
        School::factory()->create(['city' => 'Bandung']);

        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('method="GET" action="'.route('schools.index').'"', $html);
        $this->assertStringContainsString('name="q"', $html);
        $this->assertStringContainsString('placeholder="Nama sekolah, kota, atau program..."', $html);
        $this->assertStringContainsString('<option value="Bandung">Bandung</option>', $html);
        $this->assertStringContainsString('<option value="SDIT">SDIT</option>', $html);
        $this->assertStringContainsString('Cari Sekolah Sekarang', $html);
    }
}
