<article class="card-shadow group rounded-2xl bg-white transition hover:-translate-y-1">
    {{-- Image --}}
    <a href="{{ route('donations.show', $campaign->slug) }}" class="block">
        <div class="relative aspect-[16/9] overflow-hidden rounded-t-2xl">
            <img src="{{ $campaign->image_url }}" alt="{{ $campaign->title }}"
                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
            @if($campaign->is_featured)
                <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full bg-gold-500 px-2.5 py-1 text-[10px] font-bold text-white">
                    <x-app-icon name="star" class="h-3 w-3" fill="currentColor" :stroke="0"/>
                    Unggulan
                </span>
            @endif
            <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-bold text-ink">
                <x-app-icon name="heart" class="h-3 w-3 text-red-500"/>
                {{ $categories[$campaign->category] ?? $campaign->category }}
            </span>
        </div>
    </a>

    {{-- Content --}}
    <div class="p-5">
        {{-- School --}}
        <p class="text-xs font-semibold text-ink-soft">
            {{ $campaign->school->name }}
        </p>

        {{-- Title --}}
        <a href="{{ route('donations.show', $campaign->slug) }}">
            <h3 class="mt-1.5 line-clamp-2 text-base font-extrabold text-ink transition group-hover:text-forest-700">
                {{ $campaign->title }}
            </h3>
        </a>

        {{-- Progress --}}
        <div class="mt-4">
            <div class="h-2.5 w-full overflow-hidden rounded-full bg-forest-100">
                <div class="h-full rounded-full bg-gradient-to-r from-forest-700 to-forest-500 transition-all"
                     style="width: {{ $campaign->progress_percentage }}%"></div>
            </div>
            <div class="mt-2 flex items-center justify-between text-xs">
                <span class="font-bold text-forest-800">{{ $campaign->progress_percentage }}%</span>
                <span class="text-ink-soft">{{ $campaign->formatted_current }}</span>
            </div>
        </div>

        {{-- Meta --}}
        <div class="mt-4 flex items-center justify-between border-t border-forest-50 pt-4">
            <div class="flex items-center gap-1.5 text-xs text-ink-soft">
                <x-app-icon name="users" class="h-4 w-4"/>
                {{ $campaign->donors_count }} donatur
            </div>
            @if($campaign->days_left !== null)
                <div class="flex items-center gap-1.5 text-xs text-ink-soft">
                    <x-app-icon name="clock" class="h-4 w-4"/>
                    {{ $campaign->days_left }} hari
                </div>
            @endif
        </div>

        {{-- CTA --}}
        <a href="{{ route('donations.show', $campaign->slug) }}"
           class="btn-primary mt-4 w-full justify-center">
            Donasi Sekarang
        </a>
    </div>
</article>
