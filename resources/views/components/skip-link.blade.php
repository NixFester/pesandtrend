{{-- Skip-to-content link: visually hidden until focused --}}
<a {{ $attributes->merge(['class' => 'skip-link']) }}>
    {{ $slot->isEmpty() ? 'Lewati ke konten utama' : $slot }}
</a>
