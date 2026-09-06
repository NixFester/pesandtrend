{{--
  Inline validation error message.
  Accepts either a :name (looks up $errors) or a :message (direct string).
  Usage: <x-form.error name="email" />   — pulls from $errors
         <x-form.error :message="$customError" />
--}}
@props([
    'name' => null,
    'message' => null,
])
@php
    $text = $message ?? ($name ? $errors->first($name) : null);
@endphp
@if ($text)
    <p class="mt-1 text-xs font-semibold text-red-600" role="alert">{{ $text }}</p>
@endif
