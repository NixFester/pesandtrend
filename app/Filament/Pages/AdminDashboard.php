<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AdminDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.admin-dashboard';

    public function getTitle(): string
    {
        return 'Dashboard Admin';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Selamat Datang di Dashboard Admin';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola semua aspek aplikasi Pesantrends dari sini.';
    }

    protected function getViewData(): array
    {
        return [
            'navigationGroups' => $this->getNavigationGroups(),
        ];
    }

    protected function getNavigationGroups(): array
    {
        return [
            [
                'name' => 'Katalog',
                'icon' => 'heroicon-o-presentation-chart-bar',
                'description' => 'Kelola data sekolah, program pendidikan, dan fasilitas yang tersedia.',
                'items' => [
                    ['label' => 'Sekolah', 'url' => '/admin/schools', 'icon' => 'heroicon-o-academic-cap'],
                    ['label' => 'Program', 'url' => '/admin/programs', 'icon' => 'heroicon-o-bookmark'],
                    ['label' => 'Fasilitas', 'url' => '/admin/facilities', 'icon' => 'heroicon-o-building-office'],
                ],
            ],
            [
                'name' => 'Konten',
                'icon' => 'heroicon-o-document-text',
                'description' => 'Kelola artikel blog, berita, dan testimoni dari pengguna.',
                'items' => [
                    ['label' => 'Artikel', 'url' => '/admin/articles', 'icon' => 'heroicon-o-newspaper'],
                    ['label' => 'Testimoni', 'url' => '/admin/testimonials', 'icon' => 'heroicon-o-chat-bubble-left-ellipsis'],
                ],
            ],
            [
                'name' => 'SEO',
                'icon' => 'heroicon-o-magnifying-glass',
                'description' => 'Atur halaman optimasi mesin pencari untuk kota dan sekolah.',
                'items' => [
                    ['label' => 'Kota SEO', 'url' => '/admin/seo-cities', 'icon' => 'heroicon-o-map'],
                    ['label' => 'Sekolah per Kota', 'url' => '/admin/seo-city-schools', 'icon' => 'heroicon-o-list-bullet'],
                ],
            ],
            [
                'name' => 'Donasi',
                'icon' => 'heroicon-o-heart',
                'description' => 'Kelola kampanye donasi dan penggalangan dana untuk pesantren.',
                'items' => [
                    ['label' => 'Donasi', 'url' => '/admin/donations', 'icon' => 'heroicon-o-currency-dollar'],
                    ['label' => 'Campaign', 'url' => '/admin/campaigns', 'icon' => 'heroicon-o-star'],
                ],
            ],
            [
                'name' => 'Bimbel',
                'icon' => 'heroicon-o-user-group',
                'description' => 'Kelola mentor dan pemesanan bimbingan belajar online.',
                'items' => [
                    ['label' => 'Mentor', 'url' => '/admin/mentors', 'icon' => 'heroicon-o-user'],
                    ['label' => 'Pemesanan', 'url' => '/admin/mentor-bookings', 'icon' => 'heroicon-o-calendar'],
                ],
            ],
            [
                'name' => 'Onboarding',
                'icon' => 'heroicon-o-clipboard-document-check',
                'description' => 'Pantau pendaftaran dan kelola pembayaran biaya pendaftaran.',
                'items' => [
                    ['label' => 'Pendaftaran', 'url' => '/admin/onboarding-applications', 'icon' => 'heroicon-o-document-check'],
                    ['label' => 'Pembayaran', 'url' => '/admin/application-payments', 'icon' => 'heroicon-o-credit-card'],
                ],
            ],
            [
                'name' => 'Pengaturan',
                'icon' => 'heroicon-o-cog-6-tooth',
                'description' => 'Konfigurasi tampilan dan konten halaman beranda.',
                'items' => [
                    ['label' => 'Beranda', 'url' => '/admin/homepage-settings', 'icon' => 'heroicon-o-home'],
                ],
            ],
            [
                'name' => 'Akun',
                'icon' => 'heroicon-o-users',
                'description' => 'Kelola subscriber newsletter dan data pengguna.',
                'items' => [
                    ['label' => 'Subscriber', 'url' => '/admin/subscribers', 'icon' => 'heroicon-o-envelope'],
                ],
            ],
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
