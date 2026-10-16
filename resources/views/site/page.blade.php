@extends('layouts.site', ['title' => $page->title])
@section('content')
<main class="mx-auto max-w-7xl px-6 py-20">
    <article class="max-w-3xl">
        <h1 class="text-5xl font-black tracking-tight">{{ $page->header_title ?: $page->title }}</h1>
        @if($page->description)
            <div class="mt-8 whitespace-pre-line text-lg leading-8 text-slate-700">{{ strip_tags($page->description) }}</div>
        @endif
    </article>
    @if($page->image('hero', 'default', [], true))
        <img src="{{ $page->image('hero', 'default', [], true) }}" alt="{{ $page->imageAltText('hero') ?: $page->header_title ?: $page->title }}" class="mt-10 aspect-[3/1] w-full rounded-3xl object-cover" />
    @endif
    {!! $page->renderBlocks() !!}

    @if($page->page_key === 'services' && isset($pageData['services']))
        <section class="mx-auto max-w-7xl px-6 py-16">
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($pageData['services'] as $service)
                    <article class="rounded-3xl border border-slate-200 bg-white p-8">
                        <div class="text-3xl text-emerald-600" aria-hidden="true">{{ $service->icon }}</div>
                        <h2 class="mt-6 text-2xl font-black">{{ $service->title }}</h2>
                        <p class="mt-3 leading-7 text-slate-600">{{ $service->excerpt }}</p>
                        <a href="{{ route('services.show', $service) }}" class="mt-7 inline-block rounded-full bg-slate-950 px-5 py-3 text-sm font-bold text-white">Explore programme</a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($page->page_key === 'events' && isset($pageData['upcomingEvents'], $pageData['pastEvents']))
        <section class="mx-auto max-w-7xl px-6 py-16">
            <h2 class="text-3xl font-black">Upcoming events</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                @forelse($pageData['upcomingEvents'] as $event)
                    <article class="rounded-3xl border border-slate-200 bg-white p-7">
                        @if($event->image('card', 'default', [], true))
                            <img src="{{ $event->image('card', 'default', [], true) }}" alt="{{ $event->title }}" class="mb-5 h-44 w-full rounded-2xl object-cover" loading="lazy">
                        @endif
                        <div class="text-sm font-bold text-amber-700">{{ $event->starts_at?->format('d M Y') }}</div>
                        <h3 class="mt-3 text-xl font-black">{{ $event->title }}</h3>
                        <p class="mt-2 text-slate-600">{{ $event->venue }}</p>
                        <div class="mt-4 flex flex-wrap gap-3 text-xs font-bold text-slate-600">
                            @if($event->fee !== null)<span>Fee: {{ number_format((float) $event->fee, 2) }}</span>@endif
                            @if($event->capacity)<span>Participants: up to {{ number_format($event->capacity) }}</span>@endif
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="mt-5 inline-block font-bold text-emerald-700">View event →</a>
                    </article>
                @empty
                    <p class="text-slate-600">New public events are coming soon.</p>
                @endforelse
            </div>
            @if($pageData['pastEvents']->isNotEmpty())
                <h2 class="mt-14 text-3xl font-black">Past events</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach($pageData['pastEvents'] as $event)
                        <article class="rounded-3xl border border-slate-200 bg-white p-7">
                            <h3 class="text-xl font-black">{{ $event->title }}</h3>
                            <p class="mt-2 text-slate-600">{{ $event->venue }}</p>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    @if($page->page_key === 'gallery' && isset($pageData['galleryItems']))
        <section class="mx-auto max-w-7xl px-6 py-16">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($pageData['galleryItems'] as $item)
                    @if($item->image('image', 'default', [], true))
                        <figure class="overflow-hidden rounded-3xl bg-white shadow-sm">
                            <img src="{{ $item->image('image', 'default', [], true) }}" alt="{{ $item->alt_text }}" class="aspect-[4/3] w-full object-cover" loading="lazy">
                            @if($item->caption)<figcaption class="p-5 text-sm text-slate-600">{{ $item->caption }}</figcaption>@endif
                        </figure>
                    @endif
                @endforeach
            </div>
            <div class="mt-8">{{ $pageData['galleryItems']->links() }}</div>
        </section>
    @endif

    @if($page->page_key === 'testimonials' && isset($pageData['testimonials']))
        <section class="mx-auto max-w-7xl px-6 py-16">
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($pageData['testimonials'] as $testimonial)
                    <figure class="rounded-3xl bg-white p-7 ring-1 ring-slate-200">
                        <div class="text-amber-500" aria-label="{{ $testimonial->rating }} out of 5 stars">{{ str_repeat('★', $testimonial->rating) }}</div>
                        <blockquote class="mt-4 text-lg leading-7 text-slate-700">“{{ strip_tags($testimonial->description) }}”</blockquote>
                        <figcaption class="mt-6 text-sm font-extrabold">{{ $testimonial->client ?: $testimonial->title }}</figcaption>
                    </figure>
                @endforeach
            </div>
            <div class="mt-8">{{ $pageData['testimonials']->links() }}</div>
        </section>
    @endif

    @if($page->page_key === 'insights' && isset($pageData['insights']))
        <section class="mx-auto max-w-7xl px-6 py-16">
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($pageData['insights'] as $insight)
                    <article class="rounded-3xl border border-slate-200 bg-white p-7">
                        <h2 class="text-xl font-black">{{ $insight->title }}</h2>
                        <p class="mt-3 leading-7 text-slate-600">{{ $insight->excerpt }}</p>
                        <a href="{{ route('insights.show', $insight) }}" class="mt-5 inline-block font-bold text-emerald-700">Read insight →</a>
                    </article>
                @endforeach
            </div>
            <div class="mt-8">{{ $pageData['insights']->links() }}</div>
        </section>
    @endif
</main>
@endsection
