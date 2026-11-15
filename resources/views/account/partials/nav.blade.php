<nav class="account-nav" aria-label="{{ __('Kundenkonto') }}">
    <a href="{{ lroute('account.dashboard') }}" @if (request()->routeIs('account.dashboard', 'en.account.dashboard', 'account.inquiry', 'en.account.inquiry')) aria-current="page" @endif><x-glyph name="inbox" /> {{ __('Meine Anfragen') }}</a>
    <a href="{{ lroute('account.profile') }}" @if (request()->routeIs('account.profile', 'en.account.profile')) aria-current="page" @endif><x-glyph name="user" /> {{ __('Profil & Passwort') }}</a>
    <a href="{{ lroute('contact') }}"><x-glyph name="plus" /> {{ __('Neue Anfrage') }}</a>
    <form method="POST" action="{{ lroute('account.logout') }}">
        @csrf
        <button type="submit"><x-glyph name="logout" /> {{ __('Abmelden') }}</button>
    </form>
</nav>
