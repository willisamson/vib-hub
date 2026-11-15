@extends('layouts.app')

@section('title', __('Kundenkonto anlegen'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Kundenkonto anlegen'),
        'lede' => __('Einmal registrieren, dann Anfragen mit einem Klick stellen und den Bearbeitungsstand im Konto sehen.'),
        'crumbs' => [__('Kundenkonto') => lroute('account.login'), __('Registrieren') => null],
    ])

    <div class="section">
        <div class="container account-auth">
            <form class="panel" method="POST" action="{{ lroute('account.register.store') }}" novalidate>
                @csrf
                <div class="panel__body stack-m">
                    @if ($errors->any())
                        <div class="alert alert--error" role="alert">{{ __('Bitte prüfen Sie die markierten Felder.') }}</div>
                    @endif
                    <div class="form-grid">
                        @include('account.partials.field', ['name' => 'name', 'label' => __('Name'), 'autocomplete' => 'name', 'required' => true])
                        @include('account.partials.field', ['name' => 'company', 'label' => __('Firma'), 'autocomplete' => 'organization'])
                        @include('account.partials.field', ['name' => 'email', 'label' => __('E-Mail'), 'type' => 'email', 'autocomplete' => 'email', 'required' => true])
                        @include('account.partials.field', ['name' => 'phone', 'label' => __('Telefon'), 'type' => 'tel', 'autocomplete' => 'tel'])
                        @include('account.partials.field', ['name' => 'password', 'label' => __('Passwort'), 'type' => 'password', 'autocomplete' => 'new-password', 'required' => true, 'hint' => __('Mindestens 10 Zeichen.')])
                        @include('account.partials.field', ['name' => 'password_confirmation', 'label' => __('Passwort wiederholen'), 'type' => 'password', 'autocomplete' => 'new-password', 'required' => true])
                    </div>

                    <div class="hp" aria-hidden="true">
                        <label for="r-website">{{ __('Website') }}</label>
                        <input id="r-website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="privacy" value="1" @checked(old('privacy'))>
                            <span>{{ __('Ich bin einverstanden, dass meine Angaben für mein Kundenkonto und die Bearbeitung meiner Anfragen gespeichert werden. Mehr dazu in der') }} <a class="link" href="{{ lroute('privacy') }}">{{ __('Datenschutzerklärung') }}</a>.</span>
                        </label>
                        @error('privacy')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <button class="btn btn--primary" type="submit">{{ __('Konto anlegen') }}</button>
                    <p class="account-switch">{{ __('Schon registriert?') }} <a class="link" href="{{ lroute('account.login') }}">{{ __('Anmelden') }}</a></p>
                </div>
            </form>
            @include('account.partials.benefits')
        </div>
    </div>
@endsection
