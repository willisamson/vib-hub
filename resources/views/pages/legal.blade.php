@php
    $isImprint = $type === 'imprint';
    $title = $isImprint ? 'Impressum' : 'Datenschutzerklärung';
    $text = (string) $site->get($isImprint ? 'imprint' : 'privacy', '');
    $paragraphs = array_values(array_filter(preg_split("/\R{2,}/", trim($text)) ?: [], fn ($p) => trim($p) !== ''));
@endphp
@extends('layouts.app')

@section('title', $title)

@section('content')
    @include('partials.page-head', ['title' => $title, 'crumbs' => [$title => null]])

    <div class="section">
        <div class="container">
            @if (count($paragraphs))
                <div class="prose">
                    @foreach ($paragraphs as $p)
                        <p>{!! nl2br(e($p)) !!}</p>
                    @endforeach
                </div>
            @else
                <div class="empty">Der Text für „{{ $title }}“ wird im Admin unter „Website &amp; Logo“ → „Rechtliches“ gepflegt.</div>
            @endif
        </div>
    </div>
@endsection
