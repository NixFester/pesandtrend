<article class="group card-shadow overflow-hidden rounded-2xl bg-white transition-all duration-300 hover:-translate-y-1 hover:card-shadow-lg">
    <a href="{{ route('articles.show', $article->slug) }}" class="block">
        <div class="relative aspect-[16/9] overflow-hidden">
            <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
            <span class="absolute left-3 top-3 rounded-full bg-forest-900/90 px-3 py-1.5 text-[11px] font-bold text-white backdrop-blur">{{ $article->category }}</span>
        </div>
    </a>
    <div class="p-5">
        <div class="flex items-center gap-3 text-[11px] font-semibold text-ink-soft">
            <span class="flex items-center gap-1"><x-icon name="calendar" class="h-3.5 w-3.5 text-forest-600"/>{{ $article->published_label }}</span>
            <span class="flex items-center gap-1"><x-icon name="clock" class="h-3.5 w-3.5 text-forest-600"/>{{ $article->read_minutes }} min baca</span>
        </div>
        <a href="{{ route('articles.show', $article->slug) }}">
            <h3 class="mt-2.5 line-clamp-2 text-base font-extrabold leading-snug text-ink transition group-hover:text-forest-800">{{ $article->title }}</h3>
        </a>
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-ink-soft">{{ $article->excerpt }}</p>
        <a href="{{ route('articles.show', $article->slug) }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-forest-800 transition hover:gap-2.5 hover:text-forest-600">
            Baca
            <x-icon name="arrow-right" class="h-4 w-4"/>
        </a>
    </div>
</article>
