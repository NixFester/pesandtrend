{{--
  Reusable native select.
  Usage: <x-form.select name="city" label="Kota" :options="['jakarta' => 'Jakarta', ...]" :value="old('city')" placeholder="Pilih kota" />
--}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => '— Pilih —',
    'required' => false,
    'error' => null,
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

    <div class="relative mt-1">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if ($required) required @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
            @if ($hasError) class="input-field input-error w-full appearance-none pr-10" @else class="input-field w-full appearance-none pr-10" @endif
        >
            <option value="" disabled @selected($value === null || $value === '')>{{ $placeholder }}</option>
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected($value == $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>
        {{-- Chevron-down SVG — positioned absolutely inside the relative wrapper --}}
        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-ink-soft" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m6 9 6 6 6-6"/>
        </svg>
    </div>

    @if ($hasError)
        <p id="{{ $name }}-error" class="mt-1 text-xs font-semibold text-red-600" role="alert">
            {{ $error ?? $errors->first($name) }}
        </p>
    @endif
</div>
