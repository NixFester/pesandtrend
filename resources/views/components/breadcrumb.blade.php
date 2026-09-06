{{--
  Accessible breadcrumb navigation.
  Usage: <x-breadcrumb :items="[['label' => 'Beranda', 'href' => route('home')], ['label' => 'Cari Sekolah']]" />
  The last item is rendered without an href and gets aria-current="page".
--}}
@props([
    'items' => [],
])
@if (count($items))
    <nav class="text-xs font-semibold text-white/60" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1.5">
            @foreach ($items as $i => $item)
                <li class="flex items-center gap-1.5">
                    @if ($i > 0)
                        <span class="mx-1 text-white/30" aria-hidden="true">/</span>
                    @endif
                    @if ($loop->last)
                        <span class="text-gold-400" aria-current="page">{{ $item['label'] }}</span>
                    @else
                        <a href="{{ $item['href'] }}" class="transition hover:text-white">{{ $item['label'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
