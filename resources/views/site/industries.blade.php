@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Industries'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $industriesTitle = $pageContent?->header_title ?: 'Team experiences built for your context.';
    $industriesDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : 'We shape each programme around the people, pressures and outcomes that matter in your organisation.';
@endphp

<main class="industries-page">
    <section class="page-image-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image:linear-gradient(100deg,rgb(3 17 12 / 92%),rgb(3 17 12 / 75%),rgb(3 17 12 / 55%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'Our industries' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $industriesTitle }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-emerald-50/90 sm:text-lg sm:leading-8">{{ $industriesDescription }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-14 sm:py-16">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['Banking & finance', 'Build trust, communication and confident collaboration across customer-facing and operational teams.'],
                ['Technology', 'Help fast-moving product and engineering teams align, solve problems and stay connected through change.'],
                ['Healthcare', 'Create space for multidisciplinary teams to strengthen communication, trust and shared purpose.'],
                ['NGOs & development', 'Bring geographically distributed and cross-functional teams together around mission and impact.'],
                ['Education', 'Support educators and leaders to build healthy collaboration and a shared culture of learning.'],
                ['Public institutions', 'Design inclusive, practical experiences for teams serving diverse communities and stakeholders.'],
            ] as [$industry, $description])
                <article class="rounded-3xl border border-slate-200 bg-white p-8">
                    <div class="text-sm font-bold uppercase tracking-widest text-emerald-700">Tailored programmes</div>
                    <h2 class="mt-5 text-2xl font-black">{{ $industry }}</h2>
                    <p class="mt-3 leading-7 text-slate-600">{{ $description }}</p>
                    <a href="{{ route('contact') }}" class="mt-7 inline-flex font-bold text-emerald-700">Discuss your team â†’</a>
                </article>
            @endforeach
        </div>
    </section>
</main>
@endsection
