@php
    $school = $school;
@endphp
<article class="group card-shadow flex flex-col overflow-hidden rounded-3xl bg-white transition-all duration-300 hover:-translate-y-1 hover:card-shadow-lg">
    <a href="{{ route('schools.show', $school->slug) }}" class="block shrink-0">
        <div class="relative aspect-[16/10] overflow-hidden">
            <img src="{{ asset($school->image) }}" alt="Foto {{ $school->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent" aria-hidden="true"></div>

            <div class="absolute left-4 top-4 flex flex-col items-start gap-1.5">
                <span class="inline-block rounded-full bg-[#2A4B3C]/95 px-3.5 py-1.5 text-[11px] font-bold text-white shadow-sm backdrop-blur-md">
                    {{ $school->type }}
                </span>
                @if ($school->badge)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#d09d30]/95 px-3.5 py-1.5 text-[11px] font-bold text-white shadow-sm backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                        {{ Str::of($school->badge)->replace('Pendaftaran ', '') }}
                    </span>
                @endif
            </div>

            @if ($school->is_verified)
                <div class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-[#d09d30] text-white shadow-md">
                    <x-app-icon name="shield-check" class="h-5 w-5" fill="currentColor" :stroke="0"/>
                </div>
            @endif

            <div class="absolute bottom-4 right-4 flex h-8 w-8 items-center justify-center rounded-xl bg-white text-xs font-extrabold text-forest-800 shadow-md">
                A
            </div>
        </div>
    </a>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-start justify-between gap-3">
            <a href="{{ route('schools.show', $school->slug) }}" class="min-w-0 flex-1">
                <h3 class="truncate text-lg font-extrabold text-ink transition group-hover:text-forest-800">{{ $school->name }}</h3>
            </a>
            <div class="flex shrink-0 items-center gap-1 text-sm font-bold text-ink">
                <x-app-icon name="star" class="h-4 w-4 text-gold-400" fill="#C9A227" :stroke="0"/>
                {{ number_format($school->rating, 1) }}
            </div>
        </div>

        <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-ink-soft">
            <x-app-icon name="map-pin" class="h-3.5 w-3.5 shrink-0"/>
            <span class="truncate">{{ $school->city }}, {{ $school->province }}</span>
        </p>

        <div class="mt-4 flex flex-wrap gap-1.5">
            @foreach (array_slice($school->tags ?? [], 0, 3) as $tag)
                <span class="rounded-full bg-forest-50 px-2.5 py-1 text-[11px] font-bold text-forest-800">{{ $tag }}</span>
            @endforeach
        </div>

        <div class="mt-5 flex flex-1 items-end justify-between gap-2 border-t border-forest-50 pt-5">
            <div class="flex items-center gap-1.5 text-ink-soft">
                <x-app-icon name="users" class="h-4 w-4 shrink-0"/>
                <span class="text-xs font-semibold">{{ Str::of(number_format($school->students_count))->replace(',', '.') }}</span>
            </div>
            
            <div class="text-right">
                <p class="text-[9px] font-bold uppercase tracking-wider text-ink-soft/70">Mulai</p>
                <p class="text-[15px] font-extrabold text-gold-500">
                    Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}<span class="text-[10px] font-medium text-gold-500/80">/bln</span>
                </p>
            </div>
        </div>
    </div>
</article>
