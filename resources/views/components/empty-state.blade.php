{{--
  Empty state placeholder.
  Usage:
    <x-empty-state icon="send" title="Belum ada pendaftaran" body="...">
      <a href="..." class="btn-primary mt-4">Cari Sekolah</a>
    </x-empty-state>
--}}
@props([
    'icon' => 'info',
    'title' => '',
    'body' => '',
])
<div class="card-shadow rounded-2xl border border-dashed border-forest-200 bg-white p-10 text-center">
    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-forest-50 text-forest-700">
        <x-app-icon name="{{ $icon }}" class="h-7 w-7"/>
    </span>
    @if ($title)
        <h2 class="mt-4 text-lg font-extrabold text-ink">{{ $title }}</h2>
    @endif
    @if ($body)
        <p class="mx-auto mt-2 max-w-sm text-sm text-ink-soft">{{ $body }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
