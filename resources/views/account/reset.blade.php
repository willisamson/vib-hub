@extends('layouts.app')

@section('title', __('Neues Passwort festlegen'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Neues Passwort festlegen'),
        'crumbs' => [__('Kundenkonto') => lroute('account.login'), __('Neues Passwort') => null],
    ])

    <div class="section">
        <div class="container account-narrow">
            <form class="panel" method="POST" action="{{ lroute('account.password.update') }}" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="panel__body stack-m">
                    @include('account.partials.field', ['name' => 'email', 'label' => __('E-Mail'), 'type' => 'email', 'autocomplete' => 'email', 'required' => true, 'value' => old('email', $email)])
                    @include('account.partials.field', ['name' => 'password', 'label' => __('Neues Passwort'), 'type' => 'password', 'autocomplete' => 'new-password', 'required' => true, 'hint' => __('Mindestens 10 Zeichen.')])
                    @include('account.partials.field', ['name' => 'password_confirmation', 'label' => __('Passwort wiederholen'), 'type' => 'password', 'autocomplete' => 'new-password', 'required' => true])
                    <button class="btn btn--primary" type="submit">{{ __('Passwort speichern') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
