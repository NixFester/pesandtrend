@extends('layouts.app')
@section('title', $school->name.' — Pesantrends')

@section('content')
	{{-- Hero detail --}}
	<section class="relative overflow-hidden bg-forest-950">
		<img src="{{ asset($school->image) }}" alt="{{ $school->name }}" class="absolute inset-0 h-full w-full object-cover opacity-30" width="1600" height="900" loading="eager" fetchpriority="high">
		<div class="absolute inset-0 bg-gradient-to-t from-forest-950 via-forest-950/70 to-forest-950/40" aria-hidden="true"></div>

		<div class="container-app relative py-14 sm:py-20">
			<nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
				<a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
				<span class="mx-2" aria-hidden="true">/</span>
				<a href="{{ route('schools.index') }}" class="transition hover:text-white">Cari Sekolah</a>
				<span class="mx-2" aria-hidden="true">/</span>
				<span class="text-gold-400">{{ $school->name }}</span>
			</nav>

			{{-- Stacked on mobile, side-by-side on lg --}}
			<div class="mt-5 flex flex-col gap-8 lg:flex-row lg:items-start lg:gap-10">
				<div class="flex-1">
					<div class="flex flex-wrap gap-2">
						@if ($school->badge)
							<span class="inline-flex items-center gap-1.5 rounded-full bg-gold-500 px-3 py-1.5 text-[11px] font-extrabold text-forest-950">
								<x-app-icon name="calendar" class="h-3 w-3" :stroke="2.5"/>
								{{ $school->badge }}
							</span>
						@endif
						@if ($school->is_verified)
							<span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1.5 text-[11px] font-bold text-white backdrop-blur">
								<x-app-icon name="badge-check" class="h-3.5 w-3.5 text-gold-400"/>
								Terverifikasi
							</span>
						@endif
						<span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1.5 text-[11px] font-bold text-white backdrop-blur">
							Akreditasi {{ $school->accreditation }}
						</span>
					</div>

					<h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">{{ $school->name }}</h1>
					<p class="mt-3 flex items-center gap-1.5 text-sm font-medium text-white/80">
						<x-app-icon name="map-pin" class="h-4 w-4 text-gold-400"/>
						{{ $school->address }}
					</p>

					<div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-semibold text-white/90">
						<span class="flex items-center gap-1.5">
							<x-app-icon name="star" class="h-4 w-4 text-gold-400" fill="#C9A227" :stroke="0"/>
							{{ number_format($school->rating, 1) }}
							<span class="font-medium text-white/60">({{ $school->reviews_count }} ulasan)</span>
						</span>
						<span class="flex items-center gap-1.5">
							<x-app-icon name="users" class="h-4 w-4 text-gold-400"/>
							{{ Str::of(number_format($school->students_count))->replace(',', '.') }} siswa
						</span>
						<span class="flex items-center gap-1.5">
							<x-app-icon name="graduation-cap" class="h-4 w-4 text-gold-400"/>
							{{ implode(' · ', $school->jenjang) }}
						</span>
					</div>
				</div>

				{{-- Price card: below content on mobile, right sidebar on lg --}}
				<div class="card-shadow-lg w-full lg:max-w-xs rounded-2xl bg-white p-6 lg:sticky lg:top-20">
					<p class="text-xs font-semibold text-ink-soft">Mulai dari</p>
					<p class="mt-1 text-2xl font-extrabold text-forest-900">
						Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}
						<span class="text-sm font-semibold text-ink-soft">/bln</span>
					</p>
					<p class="mt-1 text-xs text-ink-soft">
						@if ($school->asrama_monthly > 0)
							+ Rp{{ number_format($school->asrama_monthly, 0, ',', '.') }} asrama/bln
						@else
							Tanpa biaya asrama
						@endif
					</p>
					<div class="mt-4 space-y-2">
						<a href="{{ route('calculator.index', [
							'pangkal' => $school->uang_pangkal,
							'spp' => $school->spp_monthly,
							'asrama' => $school->asrama_monthly,
							'seragam' => $school->seragam_fee,
							'ekskul' => $school->ekskul_fee,
							'tour' => $school->study_tour_fee,
						]) }}" class="btn-primary w-full">
							<x-app-icon name="calculator" class="h-4 w-4"/>
							Hitung Total Biaya
						</a>
						<form action="{{ route('schools.save', $school) }}" method="POST">
							@csrf
							<button type="submit" class="btn-outline w-full">
								<x-app-icon name="heart" class="h-4 w-4"/>
								{{ $isSaved ? 'Tersimpan — Hapus' : 'Simpan Sekolah' }}
							</button>
						</form>
						@if($school->whatsapp_href)
						<a href="{{ $school->whatsapp_href }}" target="_blank" rel="noopener" class="btn-whatsapp flex w-full items-center justify-center gap-2">
							<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
							Hubungi via WhatsApp
						</a>
						@endif
						@auth
						<a href="{{ route('onboarding.apply', ['school' => $school->id]) }}" class="btn-gold w-full text-center">
							<x-app-icon name="send" class="h-4 w-4"/>
							Daftar Sekarang
						</a>
						@else
						<a href="{{ route('login') }}" class="btn-gold w-full text-center">
							<x-app-icon name="send" class="h-4 w-4"/>
							Masuk untuk Mendaftar
						</a>
						@endauth
					</div>
				</div>
			</div>
		</div>
	</section>

	{{-- Sticky bottom CTA bar on mobile --}}
	<div class="fixed bottom-16 inset-x-0 z-40 border-t border-forest-100 bg-white/95 px-4 py-3 safe-bottom backdrop-blur md:hidden">
		<div class="flex gap-2">
			@if($school->whatsapp_href)
			<a href="{{ $school->whatsapp_href }}" target="_blank" rel="noopener" class="btn-whatsapp flex-1 items-center justify-center min-h-[44px]">
				<svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
				WA
			</a>
			@endif
			@auth
			<a href="{{ route('onboarding.apply', ['school' => $school->id]) }}" class="btn-gold flex-1 justify-center min-h-[44px]">
				<x-app-icon name="send" class="h-4 w-4"/>
				Daftar
			</a>
			@else
			<a href="{{ route('login') }}" class="btn-gold flex-1 justify-center min-h-[44px]">
				Daftar
			</a>
			@endauth
			<a href="{{ route('schools.show', $school->slug) }}" class="btn-outline flex-1 justify-center min-h-[44px]">
				Detail
			</a>
		</div>
	</div>

	{{-- Statistik singkat --}}
	<section class="border-b border-forest-50 bg-white">
		<div class="container-app grid grid-cols-2 gap-4 py-8 sm:grid-cols-4">
			@foreach ([
				['label' => 'Jumlah Siswa', 'value' => Str::of(number_format($school->students_count))->replace(',', '.').' siswa', 'icon' => 'users'],
				['label' => 'Rasio Guru:Siswa', 'value' => $school->teacher_ratio, 'icon' => 'graduation-cap'],
				['label' => 'Berdiri Sejak', 'value' => $school->founded_year, 'icon' => 'calendar'],
				['label' => 'Tipe', 'value' => $school->type, 'icon' => 'building'],
			] as $stat)
				<div class="flex items-center gap-3.5">
					<span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-forest-50 text-forest-800">
						<x-app-icon :name="$stat['icon']" class="h-5 w-5"/>
					</span>
					<div>
						<p class="text-[11px] font-semibold uppercase tracking-wide text-ink-soft">{{ $stat['label'] }}</p>
						<p class="text-base font-extrabold text-ink">{{ $stat['value'] }}</p>
					</div>
				</div>
			@endforeach
		</div>
	</section>

	<section class="bg-cream-50 py-12 sm:py-16">
		<div class="container-app grid gap-10 lg:grid-cols-[1fr_340px]">
			<div class="space-y-10">
				@include('components.flash')

				{{-- Tentang --}}
				<div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
					<h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
						<x-app-icon name="info" class="h-5 w-5 text-gold-600"/>
						Tentang Sekolah
					</h2>
					<div class="article-body mt-4">
						@foreach (explode("\n\n", $school->description) as $paragraph)
							<p>{{ $paragraph }}</p>
						@endforeach
					</div>
					<div class="mt-5 flex flex-wrap gap-2">
						@foreach ($school->tags as $tag)
							<span class="chip">{{ $tag }}</span>
						@endforeach
					</div>
				</div>

				{{-- Program Utama --}}
				<div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
					<h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
						<x-app-icon name="book-open" class="h-5 w-5 text-gold-600"/>
						Program Utama
					</h2>
					<div class="mt-5 grid gap-4 sm:grid-cols-2">
						@foreach ($school->programs as $program)
							<div class="flex items-start gap-3 rounded-2xl border border-forest-50 bg-cream-50 p-4">
								<span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-forest-900 text-gold-400">
									<x-app-icon :name="$program->icon" class="h-5 w-5"/>
								</span>
								<div>
									<p class="text-sm font-extrabold text-ink">{{ $program->name }}</p>
									@if ($program->description)
										<p class="mt-1 text-xs leading-relaxed text-ink-soft">{{ Str::limit($program->description, 90) }}</p>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				</div>

				{{-- Fasilitas --}}
				<div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
					<h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
						<x-app-icon name="building" class="h-5 w-5 text-gold-600"/>
						Fasilitas
					</h2>
					<div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
						@foreach ($school->facilities as $facility)
							<div class="flex items-center gap-2.5 rounded-xl bg-forest-50 px-4 py-3">
								<x-app-icon name="check-circle" class="h-4 w-4 shrink-0 text-forest-700"/>
								<span class="text-sm font-bold text-forest-900">{{ $facility->name }}</span>
							</div>
						@endforeach
					</div>
				</div>

				{{-- Prestasi --}}
				@if ($school->achievements->isNotEmpty())
				<div class="card-shadow rounded-2xl bg-white p-7 sm:p-8">
					<h2 class="flex items-center gap-2.5 text-lg font-extrabold text-ink">
						<x-app-icon name="trophy" class="h-5 w-5 text-gold-600"/>
						Prestasi &amp; Penghargaan
					</h2>
					<ul class="mt-5 space-y-3.5">
						@foreach ($school->achievements as $achievement)
							<li class="flex items-center gap-3.5 rounded-2xl border border-gold-200 bg-gold-50 px-5 py-4">
								<span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gold-500 text-forest-950">
									<x-app-icon name="trophy" class="h-5 w-5"/>
								</span>
								<p class="flex-1 text-sm font-bold text-ink">{{ $achievement->title }}</p>
								@if ($achievement->year)
									<span class="shrink-0 rounded-full bg-white px-2.5 py-1 text-xs font-extrabold text-gold-700">{{ $achievement->year }}</span>
								@endif
							</li>
						@endforeach
					</ul>
				</div>
				@endif
			</div>

			<aside class="space-y-6">
				{{-- Rincian biaya --}}
				<div class="card-shadow-lg sticky top-20 rounded-2xl bg-white p-6">
					<h2 class="flex items-center gap-2.5 text-base font-extrabold text-ink">
						<x-app-icon name="wallet" class="h-5 w-5 text-gold-600"/>
						Rincian Biaya
					</h2>
					<dl class="mt-4 space-y-3 text-sm">
						@foreach ([
							['label' => 'Uang Pangkal', 'value' => 'Rp'.number_format($school->uang_pangkal, 0, ',', '.'), 'note' => 'satu kali'],
							['label' => 'SPP Bulanan', 'value' => 'Rp'.number_format($school->spp_monthly, 0, ',', '.'), 'note' => 'per bulan'],
							['label' => 'Biaya Asrama', 'value' => $school->asrama_monthly ? 'Rp'.number_format($school->asrama_monthly, 0, ',', '.') : '—', 'note' => 'per bulan'],
							['label' => 'Seragam & Perlengkapan', 'value' => 'Rp'.number_format($school->seragam_fee, 0, ',', '.'), 'note' => 'satu kali'],
							['label' => 'Ekstrakurikuler', 'value' => 'Rp'.number_format($school->ekskul_fee, 0, ',', '.'), 'note' => 'per tahun'],
							['label' => 'Study Tour Tahunan', 'value' => 'Rp'.number_format($school->study_tour_fee, 0, ',', '.'), 'note' => 'per tahun'],
						] as $row)
							<div class="flex items-center justify-between border-b border-forest-50 pb-3 last:border-0">
								<dt class="font-semibold text-ink-soft">{{ $row['label'] }}</dt>
								<dd class="text-right">
									<span class="font-extrabold text-ink">{{ $row['value'] }}</span>
									<span class="block text-[10px] text-ink-soft">{{ $row['note'] }}</span>
								</dd>
							</div>
						@endforeach
					</dl>
					<div class="mt-4 flex items-center justify-between rounded-2xl bg-forest-900 px-5 py-4">
						<span class="text-xs font-bold text-white/80">Estimasi total/bulan</span>
						<span class="text-lg font-extrabold text-gold-400">Rp{{ number_format($school->monthly_total, 0, ',', '.') }}</span>
					</div>
					<a href="{{ route('calculator.index', [
						'pangkal' => $school->uang_pangkal,
						'spp' => $school->spp_monthly,
						'asrama' => $school->asrama_monthly,
						'seragam' => $school->seragam_fee,
						'ekskul' => $school->ekskul_fee,
						'tour' => $school->study_tour_fee,
					]) }}" class="btn-primary mt-4 w-full">
						<x-app-icon name="calculator" class="h-4 w-4"/>
						Hitung Total Biaya Lengkap
					</a>
				</div>

				{{-- Statistik alumni --}}
				@if ($school->alumni_stats)
				<div class="card-shadow rounded-2xl bg-forest-950 p-6">
					<h2 class="flex items-center gap-2.5 text-base font-extrabold text-white">
						<x-app-icon name="trending-up" class="h-5 w-5 text-gold-400"/>
						Statistik Alumni
					</h2>
					<div class="mt-4 space-y-3.5">
						@foreach ($school->alumni_stats as $label => $value)
							<div class="rounded-2xl bg-white/5 px-4 py-3">
								<p class="text-lg font-extrabold text-gold-400">{{ $value }}</p>
								<p class="mt-0.5 text-xs font-semibold text-white/70">{{ $label }}</p>
							</div>
						@endforeach
					</div>
				</div>
				@endif

				{{-- Bandingkan --}}
				<div class="card-shadow rounded-2xl border border-forest-100 bg-white p-6">
					<h2 class="text-base font-extrabold text-ink">Bandingkan dengan sekolah lain</h2>
					<p class="mt-2 text-xs leading-relaxed text-ink-soft">Tambahkan sekolah ini ke perbandingan dan lihat perbedaannya side-by-side.</p>
					<form action="{{ route('compare.add') }}" method="POST" class="mt-4">
						@csrf
						<input type="hidden" name="school_id" value="{{ $school->id }}">
						<input type="hidden" name="current" value="{{ $ids ?? '' }}">
						<button type="submit" class="btn-outline w-full">
							<x-app-icon name="scale" class="h-4 w-4"/>
							Tambah ke Perbandingan
						</button>
					</form>
				</div>
			</aside>
		</div>
	</section>

	{{-- Mini-map section --}}
	@if($school->latitude && $school->longitude)
	<section class="bg-forest-50 py-10" id="lokasi">
		<div class="container-app">
			<h2 class="flex items-center gap-2 text-lg font-extrabold text-ink">
				<x-app-icon name="map-pin" class="h-5 w-5 text-forest-600"/>
				Lokasi Sekolah
			</h2>
			<div id="school-detail-map" class="aspect-[16/9] w-full min-h-[300px] h-80 rounded-2xl overflow-hidden card-shadow z-0"></div>
			<p class="mt-3 text-sm text-ink-soft">{{ $school->address }}</p>
		</div>
	</section>
	@push('head')
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
	@endpush
	@push('scripts')
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var lat = {{ $school->latitude }};
		var lng = {{ $school->longitude }};
		var mapEl = document.getElementById('school-detail-map');
		if (!mapEl || typeof L === 'undefined') return;
		var map = L.map('school-detail-map').setView([lat, lng], 15);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '&copy; OpenStreetMap' }).addTo(map);
		L.marker([lat, lng]).addTo(map)
			.bindPopup('<strong>{{ e($school->name) }}</strong><br>{{ e($school->address) }}')
			.openPopup();
	});
	</script>
	@endpush
	@endif

	{{-- Sekolah terkait --}}
	@if ($related->count())
	<section class="bg-white py-14" aria-labelledby="terkait-heading">
		<div class="container-app">
			<div class="flex items-end justify-between gap-4">
				<h2 id="terkait-heading" class="text-xl font-extrabold tracking-tight text-ink">Sekolah Serupa</h2>
				<a href="{{ route('schools.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-forest-800 transition hover:gap-2.5">
					Lihat Semua
					<x-app-icon name="arrow-right" class="h-4 w-4"/>
				</a>
			</div>
			<div class="mt-6 auto-grid-cards">
				@foreach ($related as $school)
					<x-school-card :school="$school"/>
				@endforeach
			</div>
		</div>
	</section>
	@endif
@endsection
