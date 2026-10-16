@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'About Bizetu Hub'])

@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $aboutDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : null;
@endphp

<main @class([
    'about-page',
    'bg-transparent text-emerald-50' => $siteSettings['theme_mode'] === 'dark',
    'bg-transparent text-[#0a1f1a]' => $siteSettings['theme_mode'] !== 'dark',
])>
    <section class="about-hero relative overflow-hidden bg-[#0a1f1a] text-white">
        <div class="pointer-events-none absolute -right-28 -top-28 h-96 w-96 rounded-full bg-emerald-400/10 blur-3xl"></div>
        <div class="mx-auto grid max-w-7xl items-center gap-0 lg:min-h-[560px] lg:grid-cols-2">
            <div class="relative z-10 px-6 py-16 sm:py-20 lg:px-10 lg:py-24">
                <p class="text-sm font-extrabold uppercase tracking-[0.2em] text-amber-300">{{ $pageContent?->title ?: 'Who we are' }}</p>
                <h1 class="mt-4 max-w-2xl text-4xl font-black leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">{{ $pageContent?->header_title ?: 'People are at the heart of every organisation.' }}</h1>
                @if($aboutDescription)
                    <p class="mt-6 max-w-xl text-base leading-7 text-emerald-50/85 sm:text-lg">{{ $aboutDescription }}</p>
                @endif
                <div class="mt-8 flex flex-wrap gap-3 sm:mt-10 sm:gap-4">
                    <a href="{{ route('services') }}" class="inline-flex items-center rounded-full bg-amber-400 px-6 py-3.5 text-sm font-extrabold text-emerald-950 shadow-lg transition hover:-translate-y-0.5 hover:bg-amber-300 sm:px-8">Explore our services <span class="ml-2" aria-hidden="true">↗</span></a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full border border-emerald-300/30 px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-emerald-400/10 sm:px-8">Talk to our team</a>
                </div>
            </div>
            <div class="relative min-h-[300px] overflow-hidden sm:min-h-[400px] lg:absolute lg:inset-y-0 lg:left-1/2 lg:right-0">
                @if($heroImage)
                    <img src="{{ $heroImage }}" alt="{{ $pageContent?->imageAltText('hero') ?: 'People working together' }}" class="absolute inset-0 h-full w-full object-cover opacity-90 lg:opacity-100">
                @else
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_30%,rgb(109_167_126_/_35%),transparent_42%),linear-gradient(135deg,#164e3b,#071f17)]"></div>
                    <div class="absolute inset-0 flex items-center justify-center p-8 text-center">
                        <div class="max-w-sm rounded-3xl border border-white/15 bg-white/5 p-8 shadow-2xl backdrop-blur-sm">
                            <span class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-300">People first</span>
                            <p class="mt-3 text-2xl font-black leading-tight text-white">Stronger teams. Thriving workplaces.</p>
                        </div>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a1f1a]/50 via-transparent to-transparent lg:bg-gradient-to-l lg:from-[#0a1f1a]/30 lg:to-transparent"></div>
            </div>
        </div>
    </section>

    {!! $pageContent?->renderBlocks() !!}
</main>
@endsection
