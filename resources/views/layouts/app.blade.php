@php
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' – '.$site->name() : $site->name().' – '.$site->get('tagline');
    $description = trim($__env->yieldContent('description')) ?: $site->get('meta_description');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $site->name() }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#f2f5f7">
    <link rel="icon" href="{{ $site->faviconUrl() }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#inhalt">Zum Inhalt springen</a>

    @include('partials.header')

    <main id="inhalt">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
