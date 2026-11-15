@extends('layouts.app')

@section('title', __('Passwort vergessen'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Passwort vergessen'),
        'lede' => __('Geben Sie die E-Mail-Adresse Ihres Kundenkontos ein. Wir schicken Ihnen einen Link, mit dem Sie ein neues Passwort festlegen.'),
        'crumbs' => [__('Kundenkonto') => lroute('account.login'), __('Passwort vergessen') => null],
    ])

    <div class="section">
        <div class="container account-narrow">
            <form class="panel" method="POST" action="{{ lroute('account.password.email') }}" novalidate>
                @csrf
                <div class="panel__body stack-m">
                    @include('account.partials.field', ['name' => 'email', 'label' => __('E-Mail'), 'type' => 'email', 'autocomplete' => 'email', 'required' => true])
                    <button class="btn btn--primary" type="submit">{{ __('Link anfordern') }}</button>
                    <p class="account-switch"><a class="link" href="{{ lroute('account.login') }}">{{ __('Zurück zur Anmeldung') }}</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection
