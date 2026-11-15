@extends('layouts.app')

@section('title', 'Normen')
@section('description', 'Übersicht wichtiger Normen der Schwingungstechnik: DIN EN 60068 (Sinus, Random, Schock), ISO 16750, DIN EN 61373, MIL-STD-810, ISO 20816, DIN 4150 und mehr.')

@section('content')
    @include('partials.page-head', [
        'title' => 'Normen der Schwingungstechnik',
        'lede' => 'Die wichtigsten Prüf- und Bewertungsnormen auf einen Blick – mit typischen Parametern. Die Farbe zeigt die Signalart: Sinus, Random oder Schock.',
        'crumbs' => ['Normen' => null],
    ])

    <div class="section" style="padding-top: 2.5rem">
        <div class="container stack-m">
            <div class="filters" data-norm-filter>
                <button type="button" class="chip" data-filter="all" aria-pressed="true">Alle</button>
                @foreach ($categories as $key => $label)
                    <button type="button" class="chip" data-filter="{{ $key }}" aria-pressed="false">{{ $label }}</button>
                @endforeach
                <label class="search">
                    <span class="sr-only">Normen durchsuchen</span>
                    <x-glyph name="search" />
                    <input class="input" type="search" placeholder="Nummer oder Stichwort, z. B. 60068" data-norm-search>
                </label>
            </div>
            <p class="muted" style="font-size:.9rem"><span data-norm-count>{{ $norms->count() }}</span> Normen</p>

            <div class="norm-index">
                @foreach ($norms as $norm)
                    <a class="norm-chip" href="{{ route('norms.show', $norm) }}" data-norm data-category="{{ $norm->category }}"
                       data-text="{{ mb_strtolower($norm->code.' '.$norm->title.' '.$norm->excerpt.' '.$norm->categoryLabel()) }}">
                        <strong>{{ $norm->code }}</strong>
                        <span>{{ $norm->title }}</span>
                    </a>
                @endforeach
            </div>
            <div class="empty" data-norm-empty hidden>Keine Norm gefunden. Fehlt Ihnen eine? <a class="link" href="{{ route('contact', ['thema' => 'pruefung']) }}">Fragen Sie uns.</a></div>
            <p class="muted" style="font-size:.88rem; max-width: 70ch">Die Übersicht ersetzt nicht den Normtext. Maßgeblich ist immer die gültige Ausgabe der jeweiligen Norm bzw. Ihre Kundenspezifikation.</p>
        </div>
    </div>
@endsection
