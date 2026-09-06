{{--
  Flash message partial.
  Consumes all preserved session flash keys used throughout the app.
  Each key maps to a color variant and icon name.
--}}
@php
    $flashes = [
        'success'            => ['key' => 'success',            'variant' => 'forest', 'icon' => 'check-circle', 'class' => 'border-forest-200 bg-forest-50 text-forest-900'],
        'newsletter_success'  => ['key' => 'newsletter_success',  'variant' => 'gold',   'icon' => 'mail',           'class' => 'border-gold-200 bg-gold-50 text-gold-800'],
        'save_success'        => ['key' => 'save_success',        'variant' => 'forest', 'icon' => 'check-circle', 'class' => 'border-forest-200 bg-forest-50 text-forest-900'],
        'application_submitted'=> ['key' => 'application_submitted','variant' => 'forest', 'icon' => 'send',          'class' => 'border-forest-200 bg-forest-50 text-forest-900'],
        'payment_success'     => ['key' => 'payment_success',     'variant' => 'gold',   'icon' => 'check-circle', 'class' => 'border-gold-200 bg-gold-50 text-gold-800'],
        'application_rejected'=> ['key' => 'application_rejected','variant' => 'danger','icon' => 'info',          'class' => 'border-red-200 bg-red-50 text-red-700'],
        'profile_success'     => ['key' => 'profile_success',     'variant' => 'forest', 'icon' => 'user',          'class' => 'border-forest-200 bg-forest-50 text-forest-900'],
    ];
@endphp

@foreach ($flashes as $flash)
    @if (session($flash['key']))
        <div class="mb-6 flex items-start gap-3 rounded-2xl border p-4 {{ $flash['class'] }}" role="status">
            <x-app-icon name="{{ $flash['icon'] }}" class="mt-0.5 h-5 w-5 shrink-0" />
            <p class="text-sm font-semibold">{{ session($flash['key']) }}</p>
        </div>
    @endif
@endforeach

{{-- Validation errors --}}
@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4" role="alert">
        <ul class="list-inside list-disc text-sm font-semibold text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
