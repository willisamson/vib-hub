@extends('layouts.app')

@section('title', __('Anmelden'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Anmelden'),
        'lede' => __('Melden Sie sich an, um Anfragen zu stellen und den Stand Ihrer Anfragen zu verfolgen.'),
        'crumbs' => [__('Kundenkonto') => null],
    ])

    <div class="section">
        <div class="container account-auth">
            <form class="panel" method="POST" action="{{ lroute('account.login.store') }}" novalidate>
                @csrf
                <div class="panel__body stack-m">
                    @include('account.partials.field', ['name' => 'email', 'label' => __('E-Mail'), 'type' => 'email', 'autocomplete' => 'email', 'required' => true])
                    @include('account.partials.field', ['name' => 'password', 'label' => __('Passwort'), 'type' => 'password', 'autocomplete' => 'current-password', 'required' => true])
                    <div class="account-row">
                        <label class="check"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> <span>{{ __('Angemeldet bleiben') }}</span></label>
                        <a class="link" href="{{ lroute('account.password.request') }}">{{ __('Passwort vergessen?') }}</a>
                    </div>
                    <button class="btn btn--primary" type="submit">{{ __('Anmelden') }}</button>
                    <p class="account-switch">{{ __('Noch kein Konto?') }} <a class="link" href="{{ lroute('account.register') }}">{{ __('Jetzt registrieren') }}</a></p>
                </div>
            </form>
            @include('account.partials.benefits')
        </div>
    </div>
@endsection
