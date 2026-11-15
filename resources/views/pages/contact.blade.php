@extends('layouts.app')

@section('title', __('Kontakt & Anfrage'))
@section('description', __('Anfrage zu Schwingungsprüfung, Resonanzbeseitigung, Berechnung, Service oder Schulung stellen.'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Anfrage stellen'),
        'lede' => __('Je konkreter, desto schneller: Prüfling, Abmessungen und Masse, Norm oder Spezifikation, gewünschter Termin. Messdaten oder Zeichnungen schicken wir Ihnen gern per Upload-Link nach.'),
        'crumbs' => [__('Kontakt') => null],
    ])

    <div class="section">
        <div class="container layout-aside">
            <div>
                @if (session('sent'))
                    <div class="alert alert--ok" role="status" style="margin-bottom:1.5rem">
                        <strong>{{ __('Vielen Dank – Ihre Anfrage ist angekommen.') }}</strong><br>
                        {{ __('Wir melden uns in der Regel innerhalb eines Werktags bei Ihnen.') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert--error" role="alert" style="margin-bottom:1.5rem">
                        {{ __('Bitte prüfen Sie die markierten Felder.') }}
                    </div>
                @endif

                @if ($customer)
                    <div class="account-hint" style="margin-bottom:1.5rem">
                        <x-glyph name="user" />
                        <p>{{ __('Angemeldet als :name. Ihre Kontaktdaten sind ausgefüllt, die Anfrage erscheint anschließend in Ihrem Konto.', ['name' => $customer->name]) }}</p>
                        <a class="link" href="{{ lroute('account.dashboard') }}">{{ __('Mein Konto') }}</a>
                    </div>
                @elseif ($requiresAccount)
                    <div class="panel">
                        <div class="panel__body stack-m">
                            <h2 class="hd-3">{{ __('Bitte melden Sie sich an') }}</h2>
                            <p>{{ __('Anfragen nehmen wir über Ihr Kundenkonto entgegen. So sehen Sie jederzeit, wie weit Ihre Anfrage ist, und unsere Antworten an einem Ort.') }}</p>
                            <div class="cta__actions" style="display:flex; gap:.6rem; flex-wrap:wrap">
                                <a class="btn btn--primary" href="{{ lroute('account.register') }}">{{ __('Kundenkonto anlegen') }}</a>
                                <a class="btn" href="{{ lroute('account.login') }}">{{ __('Anmelden') }}</a>
                            </div>
                        </div>
                    </div>
                @elseif ($site->accountsEnabled())
                    <div class="account-hint" style="margin-bottom:1.5rem">
                        <x-glyph name="user" />
                        <p>{{ __('Schon Kunde? Mit Ihrem Kundenkonto sind die Kontaktdaten ausgefüllt und Sie sehen den Stand Ihrer Anfragen.') }}</p>
                        <span class="account-hint__links"><a class="link" href="{{ lroute('account.login') }}">{{ __('Anmelden') }}</a> <a class="link" href="{{ lroute('account.register') }}">{{ __('Registrieren') }}</a></span>
                    </div>
                @endif

                @if (! $requiresAccount || $customer)
                <form class="panel" method="POST" action="{{ lroute('contact.store') }}" novalidate>
                    @csrf
                    <div class="panel__body stack-m">
                        <div class="form-grid">
                            <div class="field">
                                <label for="c-name">{{ __('Name *') }}</label>
                                <input class="input" id="c-name" name="name" autocomplete="name" value="{{ old('name', $customer?->name) }}" required @error('name') aria-invalid="true" aria-describedby="e-name" @enderror>
                                @error('name')<span class="error-text" id="e-name">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label for="c-company">{{ __('Firma') }}</label>
                                <input class="input" id="c-company" name="company" autocomplete="organization" value="{{ old('company', $customer?->company) }}">
                            </div>
                            <div class="field">
                                <label for="c-email">{{ __('E-Mail *') }}</label>
                                <input class="input" id="c-email" type="email" name="email" autocomplete="email" value="{{ old('email', $customer?->email) }}" required @error('email') aria-invalid="true" aria-describedby="e-email" @enderror>
                                @error('email')<span class="error-text" id="e-email">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label for="c-phone">{{ __('Telefon') }}</label>
                                <input class="input" id="c-phone" type="tel" name="phone" autocomplete="tel" value="{{ old('phone', $customer?->phone) }}">
                            </div>
                            <div class="field full">
                                <label for="c-topic">{{ __('Thema *') }}</label>
                                <select class="select" id="c-topic" name="topic" required>
                                    @foreach ($topics as $key => $label)
                                        <option value="{{ $key }}" @selected(old('topic', $preset['topic'] ?? 'pruefung') === $key)>{{ __($label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field full">
                                <label for="c-ref">{{ __('Bezug') }}</label>
                                <input class="input" id="c-ref" name="reference" value="{{ old('reference', $preset['reference'] ?? '') }}" placeholder="{{ __('z. B. Norm, Schulungstermin, Projekt') }}">
                            </div>
                            <div class="field full">
                                <label for="c-msg">{{ __('Ihr Anliegen *') }}</label>
                                <textarea class="textarea" id="c-msg" name="message" rows="7" required @error('message') aria-invalid="true" aria-describedby="e-msg" @enderror placeholder="{{ __('Was soll geprüft, gemessen oder berechnet werden?') }}">{{ old('message') }}</textarea>
                                @error('message')<span class="error-text" id="e-msg">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="hp" aria-hidden="true">
                            <label for="c-website">{{ __('Website') }}</label>
                            <input id="c-website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="field">
                            <label style="display:flex; gap:.7rem; align-items:flex-start; font-weight:400">
                                <input type="checkbox" name="privacy" value="1" @checked(old('privacy')) style="margin-top:.3rem; width:18px; height:18px; accent-color: var(--sine)">
                                <span>{{ __('Ich bin einverstanden, dass meine Angaben zur Bearbeitung der Anfrage gespeichert werden. Mehr dazu in der') }} <a class="link" href="{{ lroute('privacy') }}">{{ __('Datenschutzerklärung') }}</a>.</span>
                            </label>
                            @error('privacy')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <button class="btn btn--primary" type="submit">{{ __('Anfrage senden') }}</button>
                        </div>
                    </div>
                </form>
                @endif
            </div>

            <aside>
                <div class="panel">
                    <div class="panel__body stack-m">
                        <h2 class="hd-3" style="font-size:1.35rem">{{ __('Direkter Draht') }}</h2>
                        <ul class="checklist">
                            @if ($site->get('email'))
                                <li><x-glyph name="mail" style="color:var(--sine)" /><a href="mailto:{{ $site->get('email') }}">{{ $site->get('email') }}</a></li>
                            @endif
                            @if ($site->get('phone'))
                                <li><x-glyph name="phone" style="color:var(--sine)" /><a href="tel:{{ preg_replace('/[^0-9+]/', '', $site->get('phone')) }}">{{ $site->get('phone') }}</a></li>
                            @endif
                            @if ($site->get('address'))
                                <li><x-glyph name="pin" style="color:var(--sine)" /><address style="font-style:normal; white-space:pre-line">{{ $site->get('address') }}</address></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel__body stack-m">
                        <h2 class="hd-3" style="font-size:1.35rem">{{ __('Das hilft uns bei Prüfanfragen') }}</h2>
                        <ul class="checklist">
                            <li><x-glyph name="check" /><span>{{ __('Masse und Abmessungen des Prüflings') }}</span></li>
                            <li><x-glyph name="check" /><span>{{ __('Norm, Prüfschärfe oder Kundenspezifikation') }}</span></li>
                            <li><x-glyph name="check" /><span>{{ __('Achsen, Betriebszustand während der Prüfung') }}</span></li>
                            <li><x-glyph name="check" /><span>{{ __('Wunschtermin und Anzahl der Prüflinge') }}</span></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
