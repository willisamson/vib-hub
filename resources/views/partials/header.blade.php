@php
    $nav = [
        ['label' => 'Leistungen', 'route' => 'services.index', 'active' => 'services.*'],
        ['label' => 'Berechnungen', 'route' => 'calculators', 'active' => 'calculators'],
        ...($site->appUrl() ? [['label' => 'App', 'route' => 'app', 'active' => 'app', 'class' => 'nav-app']] : []),
        ['label' => 'Normen', 'route' => 'norms.index', 'active' => 'norms.*'],
        ['label' => 'Schulungen', 'route' => 'trainings.index', 'active' => 'trainings.*'],
    ];
@endphp
<header class="site-header" data-header>
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ $site->name() }} – Startseite">
            <img src="{{ $site->logoUrl() }}" alt="{{ $site->name() }}" width="224" height="64">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span></span><span></span><span></span>
            <span class="sr-only">Menü</span>
        </button>
        <nav class="site-nav" id="site-nav" aria-label="Hauptnavigation" data-nav>
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}" class="{{ $item['class'] ?? '' }}" @if (request()->routeIs($item['active'])) aria-current="page" @endif>{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ route('contact') }}" class="btn btn--primary btn--small">Anfrage stellen</a>
        </nav>
    </div>
</header>
