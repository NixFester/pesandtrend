{{--
  App head partial.
  Emits all <head> tags: charset, viewport, theme-color, favicon, OG, Twitter.
  Usage: <x-app-head :title="$title ?? null" />
--}}
@php
    $title = $title ?? 'Pesantrends — Temukan Sekolah Islam Terbaik';
    $description = 'Platform terpercaya untuk menemukan, membandingkan, dan memilih sekolah Islam terbaik di Indonesia.';
    $ogImage = asset('images/og.png');
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $description }}">
{{-- Theme color matches the forest-950 navbar --}}
<meta name="theme-color" content="#0b2e1c">
{{-- Favicon — update public/favicon.svg if you have a branded SVG --}}
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
{{-- Open Graph --}}
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<title>{{ $title }}</title>
