{{--
  App head partial.
  Emits all <head> tags: charset, viewport, theme-color, favicon, OG, Twitter.
  Usage: <x-app-head :title="$title ?? null" />
  SEO Tools integration for dynamic meta tags.
--}}
@php
    $title = $title ?? 'Pesantrends — Temukan Sekolah Islam Terbaik';
    $description = 'Platform terpercaya untuk menemukan, membandingkan, dan memilih sekolah Islam terbaik di Indonesia.';
    $ogImage = asset('images/og.png');
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
{{-- Theme color matches the forest-950 navbar --}}
<meta name="theme-color" content="#0b2e1c">
{{-- Favicon — update public/favicon.svg if you have a branded SVG --}}
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">

{{-- SEO Tools rendered meta --}}
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
{!! Twitter::generate() !!}
{!! JsonLd::generate() !!}

<title>{{ $title }}</title>
