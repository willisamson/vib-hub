@extends('layouts.app')

@section('title', $page->tr('title'))
@if ($page->tr('excerpt'))
    @section('description', \Illuminate\Support\Str::limit(strip_tags($page->tr('excerpt')), 300))
@endif

@section('content')
    @include('partials.page-head', [
        'title' => $page->tr('title'),
        'lede' => $page->tr('excerpt'),
        'crumbs' => [$page->tr('title') => null],
    ])

    <div class="section">
        <div class="container">
            @if (filled(strip_tags((string) $page->tr('body'), '<img><iframe>')))
                <div class="prose">{!! consent_embeds($page->tr('body')) !!}</div>
            @else
                <div class="empty">{{ __('Dieser Text wird gerade erstellt.') }}</div>
            @endif
        </div>
    </div>
@endsection
