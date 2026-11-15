@php
    $nav = [
        ['label' => __('Leistungen'), 'route' => 'services.index', 'active' => 'services.*'],
        ['label' => __('Berechnungen'), 'route' => 'calculators', 'active' => 'calculators'],
        ['label' => __('Simulator'), 'route' => 'simulator', 'active' => 'simulator'],
        ...($site->appUrl() ? [['label' => __('App'), 'route' => 'app', 'active' => 'app', 'class' => 'nav-app']] : []),
        ['label' => __('Normen'), 'route' => 'norms.index', 'active' => 'norms.*'],
        ['label' => __('Schulungen'), 'route' => 'trainings.index', 'active' => 'trainings.*'],
    ];
@endphp
<header class="site-header" data-header>
    <div class="container site-header__inner">
        <a href="{{ lroute('home') }}" class="brand" aria-label="{{ $site->name() }} – Startseite">
            <img src="{{ $site->logoUrl() }}" alt="{{ $site->name() }}" width="224" height="64">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span></span><span></span><span></span>
            <span class="sr-only">{{ __('Menü') }}</span>
        </button>
        <nav class="site-nav" id="site-nav" aria-label="{{ __('Hauptnavigation') }}" data-nav>
            @foreach ($nav as $item)
                <a href="{{ lroute($item['route']) }}" class="{{ $item['class'] ?? '' }}" @if (request()->routeIs($item['active'], 'en.'.$item['active'])) aria-current="page" @endif>{{ $item['label'] }}</a>
            @endforeach
            <span class="lang-switch" role="group" aria-label="{{ __('Sprache') }}">
                <a href="{{ switch_locale_url('de') }}" hreflang="de" lang="de" @if (app()->getLocale() === 'de') aria-current="true" @endif>DE</a>
                <a href="{{ switch_locale_url('en') }}" hreflang="en" lang="en" @if (app()->getLocale() === 'en') aria-current="true" @endif>EN</a>
            </span>
            <a href="{{ lroute('contact') }}" class="btn btn--primary btn--small">{{ __('Anfrage stellen') }}</a>
        </nav>
    </div>
</header>
