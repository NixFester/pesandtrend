@php
    $school = $school;
    $compareIds = $compareIds ?? '';
@endphp
<article class="group card-shadow overflow-hidden rounded-2xl bg-white transition-all duration-300 hover:-translate-y-1 hover:card-shadow-lg">
    <a href="{{ route('schools.show', $school->slug) }}" class="block">
        <div class="relative aspect-[16/10] overflow-hidden">
            <img src="{{ asset($school->image) }}" alt="Foto {{ $school->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent" aria-hidden="true"></div>

            @if ($school->badge)
                <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-gold-500 px-3 py-1.5 text-[11px] font-extrabold text-forest-950 shadow-sm">
                    <x-app-icon name="calendar" class="h-3 w-3" :stroke="2.5"/>
                    {{ $school->badge }}
                </span>
            @endif

            @if ($school->is_verified)
                <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-bold text-forest-800 shadow-sm" title="Sekolah terverifikasi">
                    <x-app-icon name="badge-check" class="h-3.5 w-3.5 text-forest-700"/>
                    Terverifikasi
                </span>
            @endif

            <div class="absolute bottom-3 left-3 flex items-center gap-1.5 text-xs font-bold text-white">
                <x-app-icon name="star" class="h-4 w-4 text-gold-400" fill="#C9A227" :stroke="0"/>
                {{ number_format($school->rating, 1) }}
                <span class="font-medium text-white/80">({{ $school->reviews_count }} ulasan)</span>
            </div>
            <div class="absolute bottom-3 right-3 flex items-center gap-1 text-xs font-semibold text-white/90">
                <x-app-icon name="users" class="h-3.5 w-3.5"/>
                {{ Str::of(number_format($school->students_count))->replace(',', '.') }} siswa
            </div>
        </div>
    </a>

    <div class="p-5">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <a href="{{ route('schools.show', $school->slug) }}">
                    <h3 class="truncate text-base font-extrabold text-ink transition group-hover:text-forest-800">{{ $school->name }}</h3>
                </a>
                <p class="mt-1 flex items-center gap-1.5 text-xs font-medium text-ink-soft">
                    <x-app-icon name="map-pin" class="h-3.5 w-3.5 shrink-0 text-forest-600"/>
                    {{ $school->city }}, {{ $school->province }}
                </p>
            </div>
            <span class="shrink-0 rounded-lg bg-forest-50 px-2.5 py-1 text-[11px] font-bold text-forest-800">{{ $school->type }}</span>
        </div>

        <div class="mt-3 flex flex-wrap gap-1.5">
            @foreach (array_slice($school->tags ?? [], 0, 3) as $tag)
                <span class="rounded-full bg-cream-100 px-2.5 py-1 text-[11px] font-semibold text-forest-800">{{ $tag }}</span>
            @endforeach
        </div>

        <div class="mt-4 flex items-end justify-between border-t border-forest-50 pt-4">
            <div>
                <p class="text-[11px] font-medium text-ink-soft">Mulai dari</p>
                <p class="text-lg font-extrabold text-forest-900">
                    Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}<span class="text-xs font-semibold text-ink-soft">/bln</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('compare.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    <input type="hidden" name="current" value="{{ $compareIds }}">
                    <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-xl border border-forest-100 text-forest-700 transition hover:border-forest-700 hover:bg-forest-50" title="Tambah ke perbandingan" aria-label="Tambah {{ $school->name }} ke perbandingan">
                        <x-app-icon name="scale" class="h-4 w-4"/>
                    </button>
                </form>
                <a href="{{ route('schools.show', $school->slug) }}" class="btn-primary !px-4 !py-2 text-xs">Lihat Detail</a>
            </div>
        </div>
    </div>
</article>
