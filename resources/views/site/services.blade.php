@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Services'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
@endphp
<style>
    .services-hero-banner {
        width: 100vw;
        min-height: 430px;
        margin-left: calc(50% - 50vw);
        display: flex;
        align-items: center;
        background-position: center;
        background-size: cover;
    }

    .services-card-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.75rem;
    }

    @media (max-width: 640px) {
        .services-hero-banner {
            min-height: 360px;
        }

        .services-card-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<main
    class="services-experience relative isolate overflow-hidden text-white"
    data-services-catalogue
>
    <div class="services-glow services-glow--amber" aria-hidden="true"></div>
    <div class="services-glow services-glow--green" aria-hidden="true"></div>
    <span class="services-leaf services-leaf--one" aria-hidden="true">🍃</span>
    <span class="services-leaf services-leaf--two" aria-hidden="true">🍃</span>
    <span class="services-leaf services-leaf--three" aria-hidden="true">🍃</span>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-6">
        <section id="view-overview" class="services-view services-hero-banner mb-0 bg-emerald-950/60 px-6 py-20 sm:py-28" data-services-view="overview" aria-labelledby="services-heading" @if($heroImage) style="background-image: linear-gradient(155deg, rgb(2 44 34 / 82%), rgb(7 31 23 / 74%), rgb(3 37 29 / 84%)), url('{{ $heroImage }}');" @endif>
            <div class="mx-auto w-full max-w-7xl">
                <div class="max-w-3xl">
                <p class="mb-5 text-xl font-extrabold uppercase tracking-[0.18em] text-amber-300 sm:text-2xl">
                    {{ $pageContent?->title ?: 'Services' }}
                </p>
                <h1 id="services-heading" tabindex="-1" class="text-4xl font-extrabold leading-[1.1] tracking-tight text-white sm:text-6xl">
                    {{ $pageContent?->header_title ?: 'Designed around the way your team works.' }}
                </h1>
                <p class="mt-5 text-lg font-medium leading-relaxed text-slate-300">
                    {{ $pageContent?->description ? strip_tags($pageContent->description) : 'Every programme is practical, inclusive, and tailored to the outcomes your people need in high-impact outdoor environments.' }}
                </p>
            </div>

        </section>

        <section class="services-view pb-12 pt-6 sm:pb-16 sm:pt-8" aria-label="Service catalogue">
            @if($pageContent?->blocks->isNotEmpty())
                <div class="mb-12">
                    {!! $pageContent->renderBlocks() !!}
                </div>
            @endif

            @php
                $serviceGroups = $services->groupBy(fn ($service) => $service->category ?: 'For Organizations');
            @endphp

            @if($serviceGroups->isNotEmpty())
                <div class="mb-10 max-w-md" data-service-category-filters>
                    <h2 class="text-2xl font-extrabold tracking-tight text-amber-300 sm:text-3xl">
                        Our Services
                    </h2>
                    <label for="service-category-filter" class="sr-only">Filter by category</label>
                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <select id="service-category-filter" class="appearance-none rounded-none border border-amber-300/70 bg-emerald-950/70 px-3 py-2 text-xs font-semibold text-emerald-50 outline-none transition focus:border-amber-200 focus:ring-2 focus:ring-amber-300/30" style="width: 100%; max-width: 240px;" data-service-category-filter-select aria-label="Filter services by category">
                        <option value="">Filter by Category</option>
                        @foreach($serviceGroups->keys() as $category)
                            <option value="{{ \Illuminate\Support\Str::slug($category) }}">{{ $category }}</option>
                        @endforeach
                        </select>
                        <button type="button" class="rounded-none bg-amber-300 px-4 py-2 text-xs font-extrabold text-emerald-950 transition hover:bg-amber-200" data-service-category-search>
                            Search
                        </button>
                    </div>
                </div>
            @endif

            @if($services->isNotEmpty())
                <div class="services-card-grid" aria-label="All services">
                    @foreach($services as $service)
                    @php
                        $cover = $service->image('cover', 'default', [], true);
                        $fallbackCovers = [
                            'https://images.unsplash.com/photo-1517824806704-9040b037703b?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=900&q=80',
                        ];
                        $fallbackCover = $fallbackCovers[$loop->index % count($fallbackCovers)];
                    @endphp
                    <article class="service-glass-card group relative flex min-h-[440px] flex-col justify-between overflow-hidden rounded-3xl" data-service-card data-service-category="{{ \Illuminate\Support\Str::slug($service->category ?: 'For Organizations') }}">
                        <img src="{{ $cover ?: $fallbackCover }}" alt="{{ $service->imageAltText('cover') ?: $service->title }}" class="service-card-image absolute inset-0 h-full w-full object-cover" loading="lazy">
                        <div class="service-card-shade absolute inset-0" aria-hidden="true"></div>

                        <div class="relative z-10 p-8" aria-hidden="true">
                            <div class="h-14 w-14"></div>
                        </div>

                        <div class="relative z-10 p-8 pt-0">
                            <h2 class="text-2xl font-bold text-white transition-colors group-hover:text-amber-300">{{ $service->title }}</h2>
                            @if($service->excerpt)
                                <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $service->excerpt }}</p>
                            @endif
                            <a href="{{ route('services.show', $service) }}" data-service-open="{{ $service->slug }}" class="service-card-link btn-shimmer mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-extrabold">
                                Explore programme <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
                <nav class="mt-8 flex items-center justify-center gap-2" data-service-pagination aria-label="Services pagination"></nav>
            @else
                    <div class="service-glass-card rounded-3xl p-8 md:col-span-2 xl:col-span-3">
                        <h2 class="text-xl font-bold text-white">Our programme catalogue is being updated.</h2>
                        <p class="mt-2 text-sm leading-7 text-emerald-50/75">Tell us what your team needs and we’ll help you find a practical way forward.</p>
                        <a href="{{ route('contact') }}" class="service-card-link mt-6 inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-extrabold">Talk to our team <span aria-hidden="true">↗</span></a>
                    </div>
            @endif

            <section class="service-bottom-cta mt-14 rounded-3xl p-7 sm:mt-20 sm:p-10 lg:flex lg:items-center lg:justify-between lg:gap-10" aria-labelledby="services-cta-heading">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-amber-200">Made for your people</p>
                    <h2 id="services-cta-heading" class="mt-3 text-2xl font-black tracking-tight sm:text-3xl">Not sure which programme fits?</h2>
                    <p class="mt-3 text-sm leading-7 text-emerald-50/75 sm:text-base">Share your goals, team size and preferred timing. We’ll help shape a programme around your context.</p>
                </div>
                <a href="{{ route('book') }}" class="service-card-link mt-6 inline-flex shrink-0 items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-extrabold lg:mt-0">
                    Request a proposal <span aria-hidden="true">↗</span>
                </a>
            </section>
        </section>

        @if($services->isNotEmpty())
            <section id="view-detail" class="services-view" data-services-view="detail" aria-label="Programme details" hidden>
                <div class="mb-7 flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('services') }}" data-service-back class="service-back-link inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold">
                        <span aria-hidden="true">←</span> All programmes
                    </a>
                    <span class="text-xs font-bold uppercase tracking-[0.16em] text-emerald-100/60">Explore the programme</span>
                </div>
                <div id="service-detail-panel">
                    @include('site.partials.service-detail-content', ['service' => $services->first(), 'services' => $services, 'interactive' => true])
                </div>
            </section>

            <div class="hidden" aria-hidden="true">
                @foreach($services as $service)
                    <template data-service-detail-template="{{ $service->slug }}">
                        @include('site.partials.service-detail-content', ['service' => $service, 'services' => $services, 'interactive' => true])
                    </template>
                @endforeach
            </div>
        @endif
    </div>

    @include('site.partials.service-proposal-dialog')
</main>
@endsection
