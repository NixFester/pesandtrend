@extends('layouts.app')
@section('title', 'Sekolah Islam Terbaik di Indonesia | Pesantrends')

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">Sekolah Islam Terbaik</span>
            </nav>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Sekolah Islam Terbaik di Indonesia</h1>
            <p class="mt-2 text-sm text-white/70">Temukan pilihan sekolah Islam berkualitas dengan fasilitas lengkap dan tenaga pengajar profesional</p>
        </div>
    </section>

    <section class="bg-white py-10">
        <div class="container-app">
            <div class="mb-8">
                <h2 class="text-lg font-bold text-forest-900">Pilih Kota</h2>
                <p class="text-sm text-ink-soft/70">Temukan sekolah Islam terbaik di kota pilihan Anda</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($cities as $city)
                    <a href="{{ route('seo.schools.city', $city->slug) }}"
                       class="group relative overflow-hidden rounded-2xl border border-forest-100 bg-white p-6 transition-all hover:border-forest-300 hover:shadow-lg hover:shadow-forest-900/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-forest-900 group-hover:text-forest-700">{{ $city->name }}</h3>
                                <p class="mt-1 text-sm text-ink-soft/60">
                                    {{ $city->schools_count }} sekolah
                                    @if($city->featured_schools_count > 0)
                                        <span class="text-gold-500">• {{ $city->featured_schools_count }} pilihan utama</span>
                                    @endif
                                </p>
                            </div>
                            <svg class="h-5 w-5 text-forest-400 transition-transform group-hover:translate-x-1 group-hover:text-forest-600"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-2xl border border-forest-100 bg-forest-50/50 p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-forest-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <h3 class="mt-4 font-semibold text-forest-700">Belum Ada Kota</h3>
                        <p class="mt-1 text-sm text-ink-soft/60">Kota akan segera ditambahkan. Silakan hubungi admin.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-forest-50/50 py-10">
        <div class="container-app">
            <h2 class="mb-6 text-lg font-bold text-forest-900">Tips Memilih Sekolah Islam</h2>
            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gold-100">
                        <svg class="h-6 w-6 text-gold-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-forest-900">Akreditasi & Legalitas</h3>
                    <p class="mt-2 text-sm text-ink-soft/70">Pastikan sekolah memiliki akreditasi yang baik dan legalitas lengkap dari Kementerian Agama.</p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gold-100">
                        <svg class="h-6 w-6 text-gold-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-forest-900">Tenaga Pengajar</h3>
                    <p class="mt-2 text-sm text-ink-soft/70">Periksa kualitas dan kualifikasi guru, termasuk rasio guru terhadap siswa.</p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gold-100">
                        <svg class="h-6 w-6 text-gold-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-forest-900">Fasilitas</h3>
                    <p class="mt-2 text-sm text-ink-soft/70">Lihat fasilitas yang tersedia seperti laboratorium, perpustakaan, dan area olahraga.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
