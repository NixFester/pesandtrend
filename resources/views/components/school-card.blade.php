@php
    $school = $school;
@endphp
<article class="group relative flex min-h-[320px] flex-col justify-end overflow-hidden rounded-3xl bg-forest-950 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-forest-900/20 sm:min-h-[340px]">
    {{-- Background Image --}}
    <img src="{{ asset($school->image) }}" alt="Foto {{ $school->name }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
    
    {{-- Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-[#0d1e15] via-[#0d1e15]/80 to-transparent opacity-95 transition-opacity duration-300 group-hover:opacity-100"></div>

    {{-- Top Badges --}}
    <div class="absolute left-4 top-4 z-10 flex flex-col items-start gap-2">
        <span class="inline-block rounded-full bg-[#36634c]/95 px-3 py-1 text-[11px] font-medium text-white shadow-sm backdrop-blur-md">
            {{ $school->type }}
        </span>
        @if ($school->badge)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#d09d30]/95 px-3 py-1 text-[11px] font-medium text-white shadow-sm backdrop-blur-md">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                {{ $school->badge }}
            </span>
        @endif
    </div>

    {{-- Verified Badge --}}
    @if ($school->is_verified)
        <div class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-[#d09d30] text-white shadow-md">
            <x-app-icon name="shield-check" class="h-5 w-5" fill="currentColor" :stroke="0"/>
        </div>
    @endif

    {{-- Content --}}
    <div class="relative z-10 flex flex-col p-4 sm:p-5">
        <p class="flex items-center gap-1.5 text-[11px] font-medium text-white/80">
            <x-app-icon name="map-pin" class="h-3.5 w-3.5"/>
            <span class="truncate">{{ $school->city }}, {{ $school->province }}</span>
        </p>
        
        <h3 class="mt-1 line-clamp-2 text-lg font-extrabold leading-tight text-white sm:text-xl">
            {{ $school->name }}
        </h3>

        <div class="mt-3 flex items-end justify-between gap-2">
            <div class="flex items-center gap-3">
                {{-- Rating --}}
                <div class="flex items-center gap-1">
                    <x-app-icon name="star" class="h-3.5 w-3.5 text-gold-400" fill="#C9A227" :stroke="0"/>
                    <span class="text-sm font-bold text-white">{{ number_format($school->rating, 1) }}</span>
                    <span class="text-[10px] text-white/60">({{ $school->reviews_count }})</span>
                </div>
                
                {{-- Students --}}
                <div class="flex items-center gap-1.5">
                    <x-app-icon name="users" class="h-3.5 w-3.5 text-white/60"/>
                    <div class="flex flex-col leading-none">
                        <span class="text-xs font-medium text-white/90">{{ Str::of(number_format($school->students_count))->replace(',', '.') }}</span>
                        <span class="text-[9px] text-white/60">siswa</span>
                    </div>
                </div>
            </div>

            {{-- Price --}}
            <div class="text-right">
                <p class="text-[8px] font-bold uppercase tracking-wider text-white/60">Mulai Dari</p>
                <p class="text-sm font-extrabold text-gold-400">
                    Rp{{ number_format($school->spp_monthly, 0, ',', '.') }}<span class="text-[9px] font-medium text-gold-400/80">/bln</span>
                </p>
            </div>
        </div>

        {{-- Tags --}}
        <div class="mt-4 flex flex-wrap gap-1.5">
            @foreach (array_slice($school->tags ?? [], 0, 3) as $tag)
                <span class="rounded-full bg-white/10 px-2.5 py-1 text-[10px] font-medium text-white/90 backdrop-blur-md">
                    {{ $tag }}
                </span>
            @endforeach
        </div>
    </div>
    
    {{-- Invisible link covering the card --}}
    <a href="{{ route('schools.show', $school->slug) }}" class="absolute inset-0 z-20" aria-label="Lihat detail {{ $school->name }}"></a>
</article>
