<header class="site-header" data-header>
    <div class="container site-header__inner">
        <a href="{{ lroute('home') }}" class="brand" aria-label="{{ $site->name() }} – {{ __('Startseite') }}">
            <img src="{{ $site->logoUrl() }}" alt="{{ $site->name() }}" width="224" height="64">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span></span><span></span><span></span>
            <span class="sr-only">{{ __('Menü') }}</span>
        </button>
        <nav class="site-nav" id="site-nav" aria-label="{{ __('Hauptnavigation') }}" data-nav>
            @foreach ($menu->items('header') as $item)
                <a href="{{ $item->href() }}" @class(['nav-app' => $item->highlight]) @if ($item->isCurrent()) aria-current="page" @endif @if ($item->new_tab) target="_blank" rel="noopener" @endif>{{ $item->text() }}</a>
            @endforeach
            <span class="lang-switch" role="group" aria-label="{{ __('Sprache') }}">
                <a href="{{ switch_locale_url('de') }}" hreflang="de" lang="de" @if (app()->getLocale() === 'de') aria-current="true" @endif>DE</a>
                <a href="{{ switch_locale_url('en') }}" hreflang="en" lang="en" @if (app()->getLocale() === 'en') aria-current="true" @endif>EN</a>
            </span>
            @if ($site->accountsEnabled())
                <a href="{{ $customer ? lroute('account.dashboard') : lroute('account.login') }}" @class(['nav-account', 'is-in-account' => $customer]) title="{{ $customer ? __('Mein Konto') : __('Anmelden') }}" @if (request()->routeIs('account.*', 'en.account.*')) aria-current="page" @endif>
                    <x-glyph name="user" />
                    <span>{{ $customer ? __('Mein Konto') : __('Anmelden') }}</span>
                </a>
            @endif
            <a href="{{ lroute('contact') }}" class="btn btn--primary btn--small">{{ __('Anfrage stellen') }}</a>
        </nav>
    </div>
</header>
