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
    <link rel="alternate" hreflang="de" href="{{ switch_locale_url('de') }}">
    <link rel="alternate" hreflang="en" href="{{ switch_locale_url('en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ switch_locale_url('de') }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ app()->getLocale() === 'en' ? 'en_GB' : 'de_DE' }}">
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
    @if ($site->constructionMode() && auth('web')->check())
        <div style="position:fixed; left:0; right:0; bottom:0; z-index:70; background:#b7791f; color:#fff; font-size:.85rem; text-align:center; padding:.45rem 1rem">
            Baustellen-Modus aktiv – Besucher sehen nur die Baustellenseite. Sie sind als Admin angemeldet und sehen die ganze Website.
            <a href="{{ url('/admin/einstellungen') }}" style="color:#fff; text-decoration:underline">Ändern</a>
        </div>
    @endif
    <a class="skip-link" href="#inhalt">{{ __('Zum Inhalt springen') }}</a>

    @include('partials.header')

    <main id="inhalt">
        @if (session('status') && ! ($hideFlash ?? false))
            <div class="flash container" role="status">
                <x-glyph name="check" />
                <span>{{ session('status') }}</span>
            </div>
        @endif
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.consent')
</body>
</html>
