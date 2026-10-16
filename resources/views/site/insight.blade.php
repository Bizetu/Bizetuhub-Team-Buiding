@extends('layouts.site', ['title' => $insight->title])
@section('content')
<main>
    <section class="insight-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white">
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="max-w-4xl">
                <a href="{{ route('insights') }}" class="text-sm font-bold text-amber-300 transition hover:text-amber-200">â† All insights</a>
                <p class="mt-8 text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $insight->category }} <span class="px-2 text-emerald-300/70">Â·</span> {{ $insight->reading_time }} min read</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $insight->title }}</h1>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-6 py-14 sm:py-16">
        <article class="max-w-3xl whitespace-pre-line text-lg leading-8 text-slate-700">{{ $insight->plain_description }}</article>
    </section>
</main>
@endsection
