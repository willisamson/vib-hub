@extends('layouts.app')

@section('title', 'Leistungen')
@section('description', 'Schwingungsprüfung (Sinus, Random, Schock), Modalanalyse, FEM-Berechnung, Resonanzbeseitigung, Messungen vor Ort und Schulungen.')

@section('content')
    @include('partials.page-head', [
        'title' => 'Leistungen',
        'lede' => 'Prüfen, messen, berechnen und beheben: Wir begleiten Sie von der Spezifikation bis zum Nachweis – oder steigen genau dort ein, wo es gerade hakt.',
        'crumbs' => ['Leistungen' => null],
    ])

    <div class="section">
        <div class="container stack-l">
            @foreach (\App\Support\Catalog::SERVICE_CATEGORIES as $key => $label)
                @continue(! isset($servicesByCategory[$key]))
                <section aria-labelledby="cat-{{ $key }}" class="stack-m" style="margin-bottom: 2.5rem">
                    <h2 class="h-3" id="cat-{{ $key }}">{{ $label }}</h2>
                    <div class="cards">
                        @foreach ($servicesByCategory[$key] as $service)
                            <a class="card" data-spotlight href="{{ route('services.show', $service) }}">
                                <x-glyph :name="$service->icon" />
                                <h3>{{ $service->title }}</h3>
                                <p>{{ $service->excerpt }}</p>
                                <div class="card__foot"><span class="link">Details</span></div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
            @if ($servicesByCategory->isEmpty())
                <div class="empty">Hier erscheinen Ihre Leistungen, sobald sie im Admin angelegt sind.</div>
            @endif
        </div>
    </div>

    @include('partials.cta')
@endsection
