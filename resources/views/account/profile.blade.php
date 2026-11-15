@extends('layouts.app')

@section('title', __('Profil & Passwort'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Profil & Passwort'),
        'crumbs' => [__('Mein Konto') => lroute('account.dashboard'), __('Profil') => null],
    ])

    <div class="section">
        <div class="container account-layout">
            @include('account.partials.nav')

            <div class="account-main stack-l">
                <form class="panel" method="POST" action="{{ lroute('account.profile.update') }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="panel__head"><h2>{{ __('Kontaktdaten') }}</h2></div>
                    <div class="panel__body stack-m">
                        <div class="form-grid">
                            @include('account.partials.field', ['name' => 'name', 'label' => __('Name'), 'autocomplete' => 'name', 'required' => true, 'value' => old('name', $customer->name)])
                            @include('account.partials.field', ['name' => 'company', 'label' => __('Firma'), 'autocomplete' => 'organization', 'value' => old('company', $customer->company)])
                            @include('account.partials.field', ['name' => 'email', 'label' => __('E-Mail'), 'type' => 'email', 'autocomplete' => 'email', 'required' => true, 'value' => old('email', $customer->email)])
                            @include('account.partials.field', ['name' => 'phone', 'label' => __('Telefon'), 'type' => 'tel', 'autocomplete' => 'tel', 'value' => old('phone', $customer->phone)])
                        </div>
                        <div><button class="btn btn--primary" type="submit">{{ __('Angaben speichern') }}</button></div>
                    </div>
                </form>

                <form class="panel" method="POST" action="{{ lroute('account.password.change') }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="panel__head"><h2>{{ __('Passwort ändern') }}</h2></div>
                    <div class="panel__body stack-m">
                        <div class="form-grid">
                            @include('account.partials.field', ['bag' => 'password', 'name' => 'current_password', 'label' => __('Aktuelles Passwort'), 'type' => 'password', 'autocomplete' => 'current-password', 'required' => true, 'full' => true])
                            @include('account.partials.field', ['bag' => 'password', 'name' => 'password', 'label' => __('Neues Passwort'), 'type' => 'password', 'autocomplete' => 'new-password', 'required' => true, 'hint' => __('Mindestens 10 Zeichen.')])
                            @include('account.partials.field', ['bag' => 'password', 'name' => 'password_confirmation', 'label' => __('Passwort wiederholen'), 'type' => 'password', 'autocomplete' => 'new-password', 'required' => true])
                        </div>
                        <div><button class="btn" type="submit">{{ __('Passwort ändern') }}</button></div>
                    </div>
                </form>

                <details class="panel danger-zone" @if ($errors->getBag('delete')->any()) open @endif>
                    <summary class="panel__head"><h2>{{ __('Konto löschen') }}</h2></summary>
                    <form class="panel__body stack-m" method="POST" action="{{ lroute('account.destroy') }}" novalidate>
                        @csrf
                        @method('DELETE')
                        <p>{{ __('Ihr Zugang und Ihre Kontodaten werden sofort gelöscht. Bereits gestellte Anfragen bearbeiten wir weiter; sie sind danach nicht mehr mit einem Konto verbunden.') }}</p>
                        @include('account.partials.field', ['bag' => 'delete', 'name' => 'current_password', 'label' => __('Zur Bestätigung Ihr Passwort'), 'type' => 'password', 'autocomplete' => 'current-password', 'required' => true])
                        <div><button class="btn btn--danger" type="submit">{{ __('Konto endgültig löschen') }}</button></div>
                    </form>
                </details>
            </div>
        </div>
    </div>
@endsection
