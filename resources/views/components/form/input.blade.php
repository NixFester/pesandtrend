{{--
  Reusable form input.
  Usage: <x-form.input name="email" label="Email" type="email" :value="old('email')" placeholder="..." required error="email" hint="..." />
--}}
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'hint' => null,
])
@php
    $hasError = $error || ($errors->has($name) && old($name) !== null);
@endphp
<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-extrabold text-ink">
            {{ $label }}
            @if ($required)
                <span class="text-red-500" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value ?? old($name) }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        @if ($hasError) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        @if ($hasError) class="input-field input-error mt-1" @else class="input-field mt-1" @endif
    >

    @if ($hasError)
        <p id="{{ $name }}-error" class="mt-1 text-xs font-semibold text-red-600" role="alert">
            {{ $error ?? $errors->first($name) }}
        </p>
    @endif

    @if ($hint && ! $hasError)
        <p class="mt-1 text-xs text-ink-soft">{{ $hint }}</p>
    @endif
</div>
