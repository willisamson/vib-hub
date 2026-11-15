@extends('layouts.app')

@section('title', __('Normen'))
@section('description', __('Übersicht wichtiger Normen der Schwingungstechnik: DIN EN 60068 (Sinus, Random, Schock), ISO 16750, DIN EN 61373, MIL-STD-810, ISO 20816, DIN 4150 und mehr.'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Normen der Schwingungstechnik'),
        'lede' => __('Die wichtigsten Prüf- und Bewertungsnormen auf einen Blick – mit typischen Parametern. Die Farbe zeigt die Signalart: Sinus, Random oder Schock.'),
        'crumbs' => [__('Normen') => null],
    ])

    <div class="section" style="padding-top: 2.5rem">
        <div class="container stack-m">
            <div class="filters" data-norm-filter>
                <button type="button" class="chip" data-filter="all" aria-pressed="true">{{ __('Alle') }}</button>
                @foreach ($categories as $key => $label)
                    <button type="button" class="chip" data-filter="{{ $key }}" aria-pressed="false">{{ __($label) }}</button>
                @endforeach
                <label class="search">
                    <span class="sr-only">{{ __('Normen durchsuchen') }}</span>
                    <x-glyph name="search" />
                    <input class="input" type="search" placeholder="{{ __('Nummer oder Stichwort, z. B. 60068') }}" data-norm-search>
                </label>
            </div>
            <p class="muted" style="font-size:.9rem"><span data-norm-count>{{ $norms->count() }}</span> {{ __('Normen') }}</p>

            <div class="norm-index">
                @foreach ($norms as $norm)
                    <a class="norm-chip" href="{{ lroute('norms.show', $norm) }}" data-norm data-category="{{ $norm->category }}"
                       data-text="{{ mb_strtolower($norm->code.' '.$norm->tr('title').' '.$norm->tr('excerpt').' '.$norm->categoryLabel()) }}">
                        <strong>{{ $norm->code }}</strong>
                        <span>{{ $norm->tr('title') }}</span>
                    </a>
                @endforeach
            </div>
            <div class="empty" data-norm-empty hidden>{{ __('Keine Norm gefunden. Fehlt Ihnen eine?') }} <a class="link" href="{{ lroute('contact', ['thema' => 'pruefung']) }}">{{ __('Fragen Sie uns.') }}</a></div>
            <p class="muted" style="font-size:.88rem; max-width: 70ch">{{ __('Die Übersicht ersetzt nicht den Normtext. Maßgeblich ist immer die gültige Ausgabe der jeweiligen Norm bzw. Ihre Kundenspezifikation.') }}</p>
        </div>
    </div>
@endsection
