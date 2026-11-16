@extends('layouts.app')

@section('title', __('Leistungen'))
@section('description', __('Shaker-Prüfstände planen und resonanzfrei aufbauen, Prüfberatung (Sinus, Random, Schock), Modalanalyse, FEM-Berechnung, Resonanzbeseitigung, Messungen vor Ort und Schulungen.'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Leistungen'),
        'lede' => __('Planen, aufbauen, messen, berechnen und beheben: Wir begleiten Sie von der Spezifikation bis zum Nachweis – oder steigen genau dort ein, wo es gerade hakt.'),
        'crumbs' => [__('Leistungen') => null],
    ])

    <div class="section">
        <div class="container stack-l">
            @foreach (\App\Support\Catalog::SERVICE_CATEGORIES as $key => $label)
                @continue(! isset($servicesByCategory[$key]))
                <section aria-labelledby="cat-{{ $key }}" class="stack-m" style="margin-bottom: 2.5rem">
                    <h2 class="hd-3" id="cat-{{ $key }}">{{ __($label) }}</h2>
                    <div class="cards">
                        @foreach ($servicesByCategory[$key] as $service)
                            <a class="card" data-spotlight href="{{ lroute('services.show', $service) }}">
                                <x-glyph :name="$service->icon" />
                                <h3>{{ $service->tr('title') }}</h3>
                                <p>{{ $service->tr('excerpt') }}</p>
                                <div class="card__foot"><span class="link">{{ __('Details') }}</span></div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
            @if ($servicesByCategory->isEmpty())
                <div class="empty">{{ __('Hier erscheinen Ihre Leistungen, sobald sie im Admin angelegt sind.') }}</div>
            @endif
        </div>
    </div>

    @include('partials.cta')
@endsection
