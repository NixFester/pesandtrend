<x-filament-panels::page>
    {{-- Navigation Groups --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            Menu Navigasi
        </x-slot>
        <x-slot name="description">
            Pilih kategori untuk mengelola data
        </x-slot>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
            @foreach($navigationGroups as $group)
                <div style="border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1rem; background: white;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: #12462a; display: flex; align-items: center; justify-content: center; color: white;">
                            <x-filament::icon :icon="$group['icon']" style="width: 1.25rem; height: 1.25rem;" />
                        </div>
                        <div>
                            <h4 style="font-weight: 600; color: #111827; margin: 0;">{{ $group['name'] }}</h4>
                            <p style="font-size: 0.75rem; color: #6b7280; margin: 0;">{{ count($group['items']) }} menu</p>
                        </div>
                    </div>

                    <p style="font-size: 0.875rem; color: #4b5563; margin-bottom: 0.75rem; line-height: 1.5;">{{ $group['description'] }}</p>

                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.25rem;">
                        @foreach($group['items'] as $item)
                            <li>
                                <a href="{{ $item['url'] }}" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border-radius: 0.5rem; color: #374151; text-decoration: none; font-size: 0.875rem; transition: background 0.15s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                                    <x-filament::icon :icon="$item['icon']" style="width: 1rem; height: 1rem; color: #9ca3af;" />
                                    {{ $item['label'] }}
                                    <span style="margin-left: auto; color: #d1d5db;">→</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Quick Actions --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            Akses Cepat
        </x-slot>
        <x-slot name="description">
            Menu yang paling sering dikunjungi
        </x-slot>

        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1rem;">
            <x-filament::button tag="a" href="/admin/schools" icon="heroicon-o-academic-cap" size="sm">
                Kelola Sekolah
            </x-filament::button>
            <x-filament::button tag="a" href="/admin/articles" icon="heroicon-o-newspaper" size="sm">
                Kelola Artikel
            </x-filament::button>
            <x-filament::button tag="a" href="/admin/donations" icon="heroicon-o-heart" size="sm">
                Kelola Donasi
            </x-filament::button>
            <x-filament::button tag="a" href="/admin/onboarding-applications" icon="heroicon-o-document-check" size="sm">
                Pendaftaran
            </x-filament::button>
            <x-filament::button tag="a" href="/admin/mentor-bookings" icon="heroicon-o-calendar" size="sm">
                Pesanan Bimbel
            </x-filament::button>
            <x-filament::button tag="a" href="/admin/subscribers" icon="heroicon-o-envelope" size="sm">
                Subscriber
            </x-filament::button>
        </div>
    </x-filament::section>

    {{-- Detailed Info Sections --}}
    <x-filament::section>
        <x-slot name="heading">
            Panduan Singkat
        </x-slot>
        <x-slot name="description">
            Penjelasan detail untuk setiap menu
        </x-slot>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; margin-top: 1rem;">
            {{-- Katalog --}}
            <div style="padding: 1rem; background: #f0fdf4; border-radius: 0.75rem; border-left: 4px solid #12462a;">
                <h4 style="font-weight: 600; color: #12462a; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-presentation-chart-bar" style="width: 1.25rem; height: 1.25rem;" />
                    Katalog
                </h4>
                <p style="font-size: 0.8125rem; color: #374151; margin: 0; line-height: 1.6;">
                    Kelola data <strong>Sekolah</strong> (nama, lokasi, biaya, akreditasi), <strong>Program</strong> pendidikan, dan <strong>Fasilitas</strong> yang tersedia di setiap sekolah.
                </p>
            </div>

            {{-- Konten --}}
            <div style="padding: 1rem; background: #eff6ff; border-radius: 0.75rem; border-left: 4px solid #2563eb;">
                <h4 style="font-weight: 600; color: #2563eb; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-document-text" style="width: 1.25rem; height: 1.25rem;" />
                    Konten
                </h4>
                <p style="font-size: 0.8125rem; color: #374151; margin: 0; line-height: 1.6;">
                    Kelola <strong>Artikel</strong> untuk blog dan berita, serta <strong>Testimoni</strong> dari pengguna dan alumni sekolah.
                </p>
            </div>

            {{-- SEO --}}
            <div style="padding: 1rem; background: #fef3c7; border-radius: 0.75rem; border-left: 4px solid #d97706;">
                <h4 style="font-weight: 600; color: #d97706; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-magnifying-glass" style="width: 1.25rem; height: 1.25rem;" />
                    SEO
                </h4>
                <p style="font-size: 0.8125rem; color: #374151; margin: 0; line-height: 1.6;">
                    Atur halaman <strong>Kota SEO</strong> untuk optimasi mesin pencari dan <strong>Sekolah per Kota</strong> untuk tampilan berdasarkan lokasi.
                </p>
            </div>

            {{-- Donasi --}}
            <div style="padding: 1rem; background: #fdf2f8; border-radius: 0.75rem; border-left: 4px solid #db2777;">
                <h4 style="font-weight: 600; color: #db2777; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-heart" style="width: 1.25rem; height: 1.25rem;" />
                    Donasi
                </h4>
                <p style="font-size: 0.8125rem; color: #374151; margin: 0; line-height: 1.6;">
                    Kelola <strong>Donasi</strong> untuk kampanye penggalangan dana dan <strong>Campaign</strong> untuk mengatur target dan periode donasi.
                </p>
            </div>

            {{-- Bimbel --}}
            <div style="padding: 1rem; background: #f5f3ff; border-radius: 0.75rem; border-left: 4px solid #7c3aed;">
                <h4 style="font-weight: 600; color: #7c3aed; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-user-group" style="width: 1.25rem; height: 1.25rem;" />
                    Bimbel
                </h4>
                <p style="font-size: 0.8125rem; color: #374151; margin: 0; line-height: 1.6;">
                    Kelola <strong>Mentor</strong> bimbingan belajar dan <strong>Pemesanan</strong> untuk jadwal dan pembayaran bimbel online.
                </p>
            </div>

            {{-- Onboarding --}}
            <div style="padding: 1rem; background: #ecfdf5; border-radius: 0.75rem; border-left: 4px solid #059669;">
                <h4 style="font-weight: 600; color: #059669; margin: 0 0 0.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <x-filament::icon icon="heroicon-o-clipboard-document-check" style="width: 1.25rem; height: 1.25rem;" />
                    Onboarding
                </h4>
                <p style="font-size: 0.8125rem; color: #374151; margin: 0; line-height: 1.6;">
                    Pantau <strong>Pendaftaran</strong> calon siswa dan kelola <strong>Pembayaran</strong> biaya pendaftaran.
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
