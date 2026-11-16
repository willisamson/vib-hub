@extends('layouts.app')

@section('title', $norm->code)
@section('description', $norm->code.' – '.$norm->tr('title'))

@section('content')
    @include('partials.page-head', [
        'title' => $norm->code,
        'lede' => $norm->tr('title'),
        'crumbs' => [__('Normen') => lroute('norms.index'), $norm->code => null],
    ])

    <div class="section">
        <div class="container layout-aside">
            <article class="stack-l">
                <div class="stack-s">
                    <p><a class="chip" data-filter="{{ $norm->category }}" href="{{ lroute('norms.index', ['kategorie' => $norm->category]) }}" style="text-decoration:none">{{ $norm->categoryLabel() }}</a></p>
                    @if ($norm->tr('excerpt'))
                        <p class="lede">{{ $norm->tr('excerpt') }}</p>
                    @endif
                </div>

                @if (! empty($norm->tr('parameters')))
                    <section class="panel">
                        <div class="panel__head"><h2 class="hd-3" style="font-size:1.35rem">{{ __('Typische Parameter') }}</h2></div>
                        <div class="panel__body">
                            <dl class="spec" style="font-size:1rem; gap:.6rem 2rem">
                                @foreach ($norm->tr('parameters') as $p)
                                    <dt>{{ $p['label'] ?? '' }}</dt><dd>{{ $p['value'] ?? '' }}</dd>
                                @endforeach
                            </dl>
                        </div>
                    </section>
                @endif

                @php($requirementGroups = $norm->requirementGroups())
                @if ($requirementGroups)
                    <section class="panel requirements" id="anforderungen">
                        <div class="panel__head"><h2 class="hd-3" style="font-size:1.35rem">{{ __('Anforderungen') }}</h2></div>
                        <div class="panel__body">
                            @foreach ($requirementGroups as $group)
                                @if ($group['group'])
                                    <h3 class="requirements__group">{{ $group['group'] }}</h3>
                                @endif
                                <dl class="requirements__list">
                                    @foreach ($group['rows'] as $row)
                                        <div><dt>{{ $row['label'] }}</dt><dd>{!! nl2br(e($row['value'])) !!}</dd></div>
                                    @endforeach
                                </dl>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($norm->tr('body'))
                    <div class="prose">{!! consent_embeds($norm->tr('body')) !!}</div>
                @endif

                <p class="muted" style="font-size:.88rem">{{ __('Zusammenfassung der wesentlichen Festlegungen in eigenen Worten, ohne Gewähr. Verbindlich sind die gültige Ausgabe der Norm und Ihre Prüfspezifikation; viele Werte sind dort als Auswahl oder Vorzugswerte angegeben.') }}</p>
            </article>

            <aside>
                <div class="panel">
                    <div class="panel__body stack-m">
                        <h2 class="hd-3" style="font-size:1.35rem">{{ __(':code umsetzen', ['code' => $norm->code]) }}</h2>
                        <p class="muted">{{ __('Wir helfen Ihnen, diese Norm umzusetzen – von der Prüfschärfe über Befestigung und Regler-Setup bis zur Frage, ob Ihre Anlage das Profil schafft.') }}</p>
                        <a class="btn btn--primary" style="width:100%" href="{{ lroute('contact', ['thema' => 'pruefung', 'bezug' => $norm->code]) }}">{{ __('Beratung zu dieser Norm') }}</a>
                    </div>
                </div>
                @if ($related->isNotEmpty())
                    <div>
                        <h2 class="hd-3" style="font-size:1.2rem; margin-bottom:.8rem">{{ __('Verwandte Normen') }}</h2>
                        <div class="norm-index" style="grid-template-columns:1fr">
                            @foreach ($related as $item)
                                <a class="norm-chip" data-category="{{ $item->category }}" href="{{ lroute('norms.show', $item) }}"><strong>{{ $item->code }}</strong><span>{{ $item->tr('title') }}</span></a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
