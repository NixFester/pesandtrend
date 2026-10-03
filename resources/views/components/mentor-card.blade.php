<article class="card-shadow group rounded-2xl bg-white transition hover:-translate-y-1">
    {{-- Image --}}
    <a href="{{ route('bimbel.show', $mentor->slug) }}" class="block">
        <div class="relative aspect-[16/9] overflow-hidden rounded-t-2xl bg-forest-900">
            @if($mentor->image_url)
                <img src="{{ $mentor->image_url }}" alt="{{ $mentor->name }}"
                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
            @else
                <div class="flex h-full w-full items-center justify-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-gold-400">
                        <x-app-icon name="user" class="h-8 w-8"/>
                    </span>
                </div>
            @endif
            <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-bold text-ink">
                <x-app-icon name="wallet" class="h-3 w-3 text-forest-700"/>
                {{ $mentor->formatted_price }}
            </span>
        </div>
    </a>

    {{-- Content --}}
    <div class="p-5">
        {{-- Name --}}
        <a href="{{ route('bimbel.show', $mentor->slug) }}">
            <h3 class="line-clamp-1 text-base font-extrabold text-ink transition group-hover:text-forest-700">
                {{ $mentor->name }}
            </h3>
        </a>

        {{-- Tagline --}}
        @if($mentor->tagline)
            <p class="mt-1 line-clamp-2 text-sm text-ink-soft">{{ $mentor->tagline }}</p>
        @endif

        {{-- Expertise --}}
        @if($mentor->expertise && count($mentor->expertise) > 0)
            <div class="mt-3 flex flex-wrap gap-1.5">
                @foreach(array_slice($mentor->expertise, 0, 4) as $skill)
                    <span class="rounded-full bg-forest-50 px-2.5 py-1 text-[10px] font-bold text-forest-800">{{ $skill }}</span>
                @endforeach
            </div>
        @endif

        {{-- CTA --}}
        <a href="{{ route('bimbel.show', $mentor->slug) }}"
           class="btn-primary mt-4 w-full justify-center">
            Lihat Detail
        </a>
    </div>
</article>
