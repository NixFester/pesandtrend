{{--
  Native <dialog>-based bottom sheet for mobile filter panels.
  Usage:
    <x-mobile-sheet trigger="Filter" title="Saring sekolah" id="filter-sheet">
      <p>Your filter controls here</p>
    </x-mobile-sheet>
--}}
@props([
    'trigger' => 'Open',
    'title' => '',
    'id' => 'mobile-sheet',
])

<button
    type="button"
    class="btn-outline md:hidden"
    data-sheet-open="{{ $id }}"
    aria-haspopup="dialog"
>
    <x-app-icon name="filter" class="h-4 w-4"/>
    {{ $trigger }}
</button>

<dialog
    id="{{ $id }}"
    class="fixed inset-0 z-50 h-auto max-h-[85dvh] w-full max-w-lg mx-auto rounded-b-none rounded-t-2xl border border-forest-100 bg-white p-0 shadow-2xl backdrop:bg-black/40"
    style="margin-top: auto; margin-bottom: 0; padding: 0;"
>
    {{-- Header with handle + title + close --}}
    <div class="sticky top-0 z-10 flex items-center justify-between gap-4 border-b border-forest-50 bg-white px-5 py-4 safe-top">
        <div class="flex items-center gap-3">
            {{-- Drag handle --}}
            <span class="flex h-1 w-8 items-center justify-center" aria-hidden="true">
                <span class="block h-1 w-8 rounded-full bg-forest-200"></span>
            </span>
            @if ($title)
                <h2 class="text-base font-extrabold text-ink">{{ $title }}</h2>
            @endif
        </div>
        <button
            type="button"
            class="flex h-8 w-8 items-center justify-center rounded-lg text-ink-soft transition hover:bg-forest-50 hover:text-ink"
            data-sheet-close
            aria-label="Tutup"
        >
            <x-app-icon name="x" class="h-5 w-5"/>
        </button>
    </div>

    {{-- Scrollable content --}}
    <div class="overflow-y-auto px-5 py-4 safe-bottom" style="max-height: calc(85dvh - 80px);">
        {{ $slot }}
    </div>
</dialog>
