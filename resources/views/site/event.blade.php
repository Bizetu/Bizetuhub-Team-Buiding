@extends('layouts.site', ['title' => $event->title])
@section('content')
<style>
    .site-theme > footer {
        margin-top: 0 !important;
    }
</style>
<main class="mx-auto max-w-5xl px-6 py-0">
    <a href="{{ route('events') }}" class="text-sm font-bold text-emerald-700">← All events</a>
    <article class="max-w-3xl py-12">
        <div class="text-sm font-bold uppercase tracking-widest text-emerald-700">{{ $event->starts_at?->format('l, d F Y · H:i') }}</div>
        <h1 class="mt-4 text-5xl font-black tracking-tight">{{ $event->title }}</h1>
        @if($event->image('card', 'default', [], true))
            <img src="{{ $event->image('card', 'default', [], true) }}" alt="{{ $event->title }}" class="mt-8 w-full rounded-3xl object-cover" loading="lazy">
        @endif
        <div class="mt-5 text-lg font-semibold text-slate-500">{{ $event->venue }}</div>
        <div class="mt-8 whitespace-pre-line text-lg leading-8 text-slate-700">{{ strip_tags($event->description) }}</div>
        @if($event->starts_at?->isFuture())
            <div class="mt-6 flex flex-wrap gap-4 text-sm font-semibold text-slate-600">
                @if($event->fee !== null)<span>Fee: {{ number_format((float) $event->fee, 2) }}</span>@endif
                @if($event->capacity)<span>Places available: up to {{ number_format($event->capacity) }}</span>@endif
            </div>
        @endif
        <a href="{{ route('book', ['event_id' => $event->id]) }}" class="mt-10 inline-flex rounded-full bg-emerald-600 px-6 py-4 font-bold text-white hover:bg-emerald-700">Register your interest ↗</a>
    </article>
</main>
@endsection
