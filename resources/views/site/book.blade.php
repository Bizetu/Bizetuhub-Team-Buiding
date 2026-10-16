@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Book a session'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $heroDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : 'This is a request, not a commitment. We will reply with availability and a tailored recommendation.';
@endphp

<main>
    <section class="page-image-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image:linear-gradient(100deg,rgb(3 17 12 / 92%),rgb(3 17 12 / 75%),rgb(3 17 12 / 55%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'Start here' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $pageContent?->header_title ?: 'Tell us what your team needs.' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-emerald-50/90 sm:text-lg sm:leading-8">{{ $heroDescription }}</p>
            </div>
        </div>
    </section>

    @if($pageContent?->blocks?->isNotEmpty())
        <section class="mx-auto max-w-5xl px-6 pt-12">
            {!! $pageContent->renderBlocks() !!}
        </section>
    @endif

    <section class="mx-auto max-w-5xl px-6 py-14 sm:py-16">
        <form method="POST" action="{{ route('book.store') }}" class="grid gap-6 rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200 md:grid-cols-2">
            @csrf
            @foreach([
                ['organisation', 'Organisation', 'text', true],
                ['name', 'Your name', 'text', true],
                ['email', 'Work email', 'email', true],
                ['phone', 'Phone number', 'tel', true],
                ['preferred_date', 'Preferred date', 'date', true],
                ['participants', 'Number of participants', 'number', true],
                ['venue_preference', 'Venue preference', 'text', false],
            ] as [$name, $label, $type, $required])
                <label class="block text-sm font-bold text-slate-700">
                    {{ $label }}
                    <input
                        name="{{ $name }}"
                        type="{{ $type }}"
                        value="{{ old($name) }}"
                        @if($required) required @endif
                        @if($name === 'preferred_date') min="{{ now()->toDateString() }}" @endif
                        @if($name === 'participants') min="2" max="10000" @endif
                        class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 font-normal shadow-sm"
                    >
                    @error($name)<span class="mt-1 block text-xs font-normal text-rose-600">{{ $message }}</span>@enderror
                </label>
            @endforeach
            <label class="block text-sm font-bold text-slate-700">
                Programme <span class="font-normal text-slate-500">(select a programme or an event)</span>
                <select name="service_id" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 font-normal shadow-sm">
                    <option value="">Select a programme</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @selected((string) old('service_id', request('service_id')) === (string) $service->id)>{{ $service->title }}</option>
                    @endforeach
                </select>
                @error('service_id')<span class="mt-1 block text-xs font-normal text-rose-600">{{ $message }}</span>@enderror
            </label>
            <label class="block text-sm font-bold text-slate-700">
                Public event <span class="font-normal text-slate-500">(optional)</span>
                <select name="event_id" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 font-normal shadow-sm">
                    <option value="">Not registering for a public event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" @selected((string) old('event_id', request('event_id')) === (string) $event->id)>
                            {{ $event->title }} - {{ $event->starts_at?->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
                @error('event_id')<span class="mt-1 block text-xs font-normal text-rose-600">{{ $message }}</span>@enderror
            </label>
            <label class="block text-sm font-bold text-slate-700 md:col-span-2">
                Anything we should know?
                <textarea name="requirements" rows="4" class="mt-2 w-full rounded-xl border-slate-300 px-4 py-3 font-normal shadow-sm">{{ old('requirements') }}</textarea>
                @error('requirements')<span class="mt-1 block text-xs font-normal text-rose-600">{{ $message }}</span>@enderror
            </label>
            <button class="rounded-full bg-emerald-600 px-6 py-4 font-bold text-white hover:bg-emerald-700 md:col-span-2 md:w-fit">Send booking request</button>
        </form>
    </section>
</main>
@endsection
