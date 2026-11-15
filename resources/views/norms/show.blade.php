@extends('layouts.app')

@section('title', $norm->code)
@section('description', $norm->code.' – '.$norm->title)

@section('content')
    @include('partials.page-head', [
        'title' => $norm->code,
        'lede' => $norm->title,
        'crumbs' => ['Normen' => route('norms.index'), $norm->code => null],
    ])

    <div class="section">
        <div class="container layout-aside">
            <article class="stack-l">
                <div class="stack-s">
                    <p><a class="filter" data-filter="{{ $norm->category }}" href="{{ route('norms.index', ['kategorie' => $norm->category]) }}" style="text-decoration:none">{{ $norm->categoryLabel() }}</a></p>
                    @if ($norm->excerpt)
                        <p class="lede">{{ $norm->excerpt }}</p>
                    @endif
                </div>

                @if (! empty($norm->parameters))
                    <section class="panel">
                        <div class="panel__head"><h2 class="h-3" style="font-size:1.35rem">Typische Parameter</h2></div>
                        <div class="panel__body">
                            <dl class="spec" style="font-size:1rem; gap:.6rem 2rem">
                                @foreach ($norm->parameters as $p)
                                    <dt>{{ $p['label'] ?? '' }}</dt><dd>{{ $p['value'] ?? '' }}</dd>
                                @endforeach
                            </dl>
                        </div>
                    </section>
                @endif

                @if ($norm->body)
                    <div class="prose">{!! $norm->body !!}</div>
                @endif

                <p class="muted" style="font-size:.88rem">Angaben ohne Gewähr. Maßgeblich ist die gültige Ausgabe der Norm bzw. Ihre Kundenspezifikation.</p>
            </article>

            <aside>
                <div class="panel">
                    <div class="panel__body stack-m">
                        <h2 class="h-3" style="font-size:1.35rem">Prüfung nach {{ $norm->code }}</h2>
                        <p class="muted">Wir prüfen nach dieser Norm oder leiten mit Ihnen die passende Prüfschärfe ab.</p>
                        <a class="btn btn--primary" style="width:100%" href="{{ route('contact', ['thema' => 'pruefung', 'bezug' => $norm->code]) }}">Prüfung anfragen</a>
                    </div>
                </div>
                @if ($related->isNotEmpty())
                    <div>
                        <h2 class="h-3" style="font-size:1.2rem; margin-bottom:.8rem">Verwandte Normen</h2>
                        <div class="norm-index" style="grid-template-columns:1fr">
                            @foreach ($related as $item)
                                <a class="norm-chip" data-category="{{ $item->category }}" href="{{ route('norms.show', $item) }}"><strong>{{ $item->code }}</strong><span>{{ $item->title }}</span></a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
