@extends('layouts.app')
@section('title', $seoCity->meta_title ?? "Sekolah Islam Terbaik di {$seoCity->name} | Pesantrends")

@section('content')
    <section class="bg-forest-950 py-12">
        <div class="container-app">
            <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <a href="{{ route('seo.schools.best') }}" class="transition hover:text-white">Sekolah Islam</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-gold-400">{{ $seoCity->name }}</span>
            </nav>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Sekolah Islam Terbaik di {{ $seoCity->name }}</h1>
            <p class="mt-2 text-sm text-white/70">{{ $seoCity->meta_description ?? "Temukan pilihan sekolah Islam berkualitas di {$seoCity->name}" }}</p>
        </div>
    </section>

    <section class="bg-white py-10">
        <div class="container-app">
            @if($schools->count() > 0)
                <div class="mb-6 flex items-center justify-between">
                    <p class="text-sm text-ink-soft/70">{{ $schools->count() }} sekolah ditemukan</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
                    @foreach($schools as $index => $school)
                        @php
                            $rank = $index + 1;
                            $rankClass = match(true) {
                                $rank === 1 => 'bg-gradient-to-br from-amber-400 to-yellow-500 text-amber-900',
                                $rank === 2 => 'bg-gradient-to-br from-slate-300 to-slate-400 text-slate-800',
                                $rank === 3 => 'bg-gradient-to-br from-amber-600 to-amber-700 text-amber-100',
                                default => 'bg-forest-100 text-forest-700'
                            };
                        @endphp
                        <a href="{{ route('schools.show', $school->slug) }}"
                           class="group relative overflow-hidden rounded-2xl border border-forest-100 bg-white transition-all hover:border-forest-300 hover:shadow-xl hover:shadow-forest-900/10">
                            @if($rank <= 3)
                                <div class="absolute left-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold shadow-sm {{ $rankClass }}">
                                    #{{ $rank }}
                                </div>
                            @endif

                            <div class="aspect-[16/9] overflow-hidden bg-forest-100">
                                <img src="{{ $school->image_url }}"
                                     alt="{{ $school->name }}"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            </div>

                            <div class="p-5">
                                <div class="mb-2 flex items-center gap-2">
                                    @if($school->is_verified)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-forest-100 px-2 py-0.5 text-xs font-medium text-forest-700">
                                            <svg class="h-3 w-3 text-forest-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                                            </svg>
                                            Terverifikasi
                                        </span>
                                    @endif
                                    @if($school->is_featured)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gold-100 px-2 py-0.5 text-xs font-medium text-gold-700">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Pilihan Utama
                                        </span>
                                    @endif
                                </div>

                                <h3 class="line-clamp-2 text-lg font-bold text-forest-900 group-hover:text-forest-700">{{ $school->name }}</h3>

                                <div class="mt-2 flex items-center gap-2 text-sm text-ink-soft/60">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $school->city }}
                                </div>

                                @if($school->rating)
                                    <div class="mt-3 flex items-center gap-2">
                                        <div class="flex items-center gap-1">
                                            <svg class="h-4 w-4 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="font-semibold text-forest-900">{{ number_format($school->rating, 1) }}</span>
                                        </div>
                                        <span class="text-sm text-ink-soft/60">({{ $school->reviews_count }} ulasan)</span>
                                    </div>
                                @endif

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @if($school->jenjang)
                                        @foreach(array_slice($school->jenjang, 0, 3) as $level)
                                            <span class="rounded-full bg-forest-50 px-2.5 py-1 text-xs font-medium text-forest-700">{{ $level }}</span>
                                        @endforeach
                                    @endif
                                    @if($school->is_boarding)
                                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">Berasrama</span>
                                    @endif
                                </div>

                                <div class="mt-4 flex items-center justify-between border-t border-forest-100 pt-4">
                                    <div>
                                        <p class="text-xs text-ink-soft/50">Uang Pangkal</p>
                                        <p class="font-semibold text-forest-900">
                                            @if($school->uang_pangkal)
                                                Rp {{ number_format($school->uang_pangkal, 0, ',', '.') }}
                                            @else
                                                Hubungi Sekolah
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-ink-soft/50">SPP Bulanan</p>
                                        <p class="font-semibold text-forest-900">
                                            @if($school->spp_monthly)
                                                Rp {{ number_format($school->spp_monthly, 0, ',', '.') }}
                                            @else
                                                Hubungi Sekolah
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-forest-100 bg-forest-50/50 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-forest-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-forest-700">Belum Ada Sekolah di {{ $seoCity->name }}</h3>
                    <p class="mt-2 text-sm text-ink-soft/60">Saat ini belum ada sekolah yang terdaftar di kota ini. Silakan cek kota lain.</p>
                    <a href="{{ route('seo.schools.best') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-forest-600 px-6 py-3 font-semibold text-white transition-colors hover:bg-forest-700">
                        Lihat Kota Lain
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="bg-forest-50/50 py-10">
        <div class="container-app">
            <h2 class="mb-6 text-lg font-bold text-forest-900">Pertanyaan Umum tentang Sekolah Islam</h2>
            <div class="space-y-4">
                <details class="group rounded-xl border border-forest-200 bg-white">
                    <summary class="flex cursor-pointer items-center justify-between p-5 font-semibold text-forest-900">
                        Apa keuntungan sekolah Islam dibandingkan sekolah umum?
                        <svg class="h-5 w-5 transition-transform group-open:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="border-t border-forest-100 p-5 text-sm text-ink-soft/70">
                        Sekolah Islam memberikan pendidikan akademik berkualitas的同时注重 karakter dan nilai-nilai Islam. Siswa mendapat pendidikan moral, spiritual, dan akhlak yang terintegrasi dengan kurikulum nasional.
                    </div>
                </details>
                <details class="group rounded-xl border border-forest-200 bg-white">
                    <summary class="flex cursor-pointer items-center justify-between p-5 font-semibold text-forest-900">
                        Bagaimana dengan biaya sekolah Islam?
                        <svg class="h-5 w-5 transition-transform group-open:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="border-t border-forest-100 p-5 text-sm text-ink-soft/70">
                        Biaya bervariasi tergantung sekolah. Pesantrends menyediakan informasi lengkap tentang uang pangkal, SPP bulanan, dan biaya lainnya agar Anda bisa membandingkan dan memilih sesuai anggaran.
                    </div>
                </details>
                <details class="group rounded-xl border border-forest-200 bg-white">
                    <summary class="flex cursor-pointer items-center justify-between p-5 font-semibold text-forest-900">
                        Apakah sekolah Islam menerima siswa non-Muslim?
                        <svg class="h-5 w-5 transition-transform group-open:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="border-t border-forest-100 p-5 text-sm text-ink-soft/70">
                        Beberapa sekolah Islam menerima siswa dari berbagai latar belakang. Namun kebijakan ini berbeda-beda antar sekolah. Silakan hubungi sekolah terkait untuk informasi lebih lanjut.
                    </div>
                </details>
            </div>
        </div>
    </section>
@endsection
