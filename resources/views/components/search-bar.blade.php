{{--
  Search + filter bar used on home, schools, and articles pages.
  Usage:
    <x-search-bar :action="route('schools.index')">
      <x-slot:filters>
        <x-form.select name="kota" :options="$cities" />
      </x-slot:filters>
    </x-search-bar>
--}}
@props([
    'action' => '#',
    'q' => '',
])
<form method="GET" action="{{ $action }}" class="card-shadow grid gap-2 rounded-2xl border border-forest-100 bg-white p-3 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr_auto]">
    {{-- Search input --}}
    <label class="relative flex items-center">
        <svg class="pointer-events-none absolute left-4 h-5 w-5 text-ink-soft/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Nama sekolah, kota, atau program..."
            aria-label="Cari sekolah"
            class="w-full rounded-xl border-0 bg-forest-50/60 py-3 pl-12 pr-4 text-sm font-medium text-ink placeholder:text-ink-soft/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-500/30"
        >
    </label>

    {{-- Dynamic filter slots --}}
    {{ $filters ?? '' }}

    {{-- Submit button --}}
    <button type="submit" class="btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        Terapkan
    </button>
</form>
