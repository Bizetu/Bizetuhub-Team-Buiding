@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Corporate packages'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $heroDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : 'Every engagement is scoped to your group size, goals, schedule and venue. Tell us what you have in mind and weâ€™ll prepare a tailored proposal.';
@endphp
<main>
    <section class="page-image-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image:linear-gradient(100deg,rgb(3 17 12 / 92%),rgb(3 17 12 / 75%),rgb(3 17 12 / 55%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'Corporate packages' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $pageContent?->header_title ?: 'A format that fits your team.' }}</h1>
                <p class="mt-5 text-base leading-7 text-emerald-50/90 sm:text-lg sm:leading-8">{{ $heroDescription }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-14 sm:py-16">
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach([
                ['Team session', 'A focused, facilitated session for teams looking to reconnect and improve how they work together.', 'Small teams and departments', 'Half-day or full-day options'],
                ['Organisation day', 'A shared experience to connect multiple teams, celebrate progress and strengthen collaboration.', 'Cross-functional groups', 'Flexible event formats'],
                ['Culture programme', 'A series of workshops and practical activities designed around longer-term people and culture goals.', 'Organisations planning sustained change', 'Multi-session programme'],
            ] as [$name, $description, $audience, $format])
                <article class="flex flex-col rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <h2 class="text-2xl font-black">{{ $name }}</h2>
                    <p class="mt-4 flex-1 leading-7 text-slate-600">{{ $description }}</p>
                    <dl class="mt-8 space-y-4 border-t border-slate-200 pt-6 text-sm">
                        <div><dt class="font-bold">Designed for</dt><dd class="mt-1 text-slate-600">{{ $audience }}</dd></div>
                        <div><dt class="font-bold">Format</dt><dd class="mt-1 text-slate-600">{{ $format }}</dd></div>
                    </dl>
                    <a href="{{ route('book') }}" class="mt-8 inline-flex w-fit rounded-full bg-slate-950 px-5 py-3 text-sm font-bold text-white">Request a proposal â†—</a>
                </article>
            @endforeach
        </div>
        <p class="mx-auto mt-8 max-w-3xl text-center text-sm leading-6 text-slate-500">Pricing is provided in a tailored proposal after we understand your requirements. No package is booked or confirmed until agreed with our team.</p>
    </section>
</main>
@endsection
