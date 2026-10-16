@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Events'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $pageTitle = $pageContent?->header_title ?: $pageContent?->title ?: 'Events';
    $pageDescription = $pageContent?->description;
    $allEvents = $upcomingEvents->concat($pastEvents);
@endphp

<style>
    :root { --bg-deep-green:#0A1F1A; --bg-card:#112B24; --accent-yellow:#D4A017; --accent-yellow-hover:#E5B22A; --text-cream:#F8F9FA; --text-muted:#A3B8B2; --border-color:rgba(212,160,23,.2); }
    .site-theme > footer { margin-top: 0 !important; }
    .site-theme main section + section { margin-top: 0 !important; }
    .site-theme main section + section::before { display: none; }
    .events-hero { display:flex; min-height:350px; align-items:center; }
    .events-hero > div { width:100%; }
    .events-reference { background-color:var(--bg-deep-green); color:var(--text-cream); padding:48px 24px 64px; }
    .events-reference .filters { display:flex; gap:8px; margin:0 auto 48px; width:max-content; max-width:100%; background:rgba(255,255,255,.03); padding:6px; border-radius:100px; border:1px solid rgba(255,255,255,.05); backdrop-filter:blur(10px); }
    .events-reference .filter-btn { padding:13px 32px; border:0; background:transparent; border-radius:100px; font-family:inherit; font-weight:600; font-size:.9rem; color:var(--text-muted); cursor:pointer; transition:all .2s ease; }
    .events-reference .filter-btn.active { background:var(--accent-yellow); color:var(--bg-deep-green); box-shadow:0 4px 15px rgba(212,160,23,.2); }
    .events-reference .filter-btn:hover:not(.active) { color:var(--text-cream); background:rgba(255,255,255,.08); }
    .events-reference .events-container { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:24px; max-width:1230px; width:100%; margin:0 auto; }
    .events-reference .event-card { background:var(--bg-card); border:1px solid rgba(255,255,255,.04); border-radius:20px; overflow:hidden; transition:transform .3s ease,border-color .3s ease,box-shadow .3s ease; display:flex; flex-direction:column; box-shadow:0 10px 30px rgba(0,0,0,.2); backdrop-filter:blur(5px); }
    .events-reference .event-card:hover { transform:translateY(-5px); border-color:var(--border-color); box-shadow:0 15px 40px rgba(0,0,0,.4); }
    .events-reference .card-image-wrapper { position:relative; height:264px; width:100%; }
    .events-reference .card-image-wrapper img { width:100%; height:100%; object-fit:cover; filter:brightness(.9) contrast(1.1); transition:filter .3s ease; }
    .events-reference .event-card:hover .card-image-wrapper img { filter:brightness(1) contrast(1.1); }
    .events-reference .date-overlay { position:absolute; bottom:12px; left:8px; background:rgba(10,31,26,.94); border:1px solid rgba(212,160,23,.3); padding:8px 14px; border-radius:100px; font-size:.75rem; font-weight:700; color:var(--accent-yellow); letter-spacing:.5px; text-transform:uppercase; backdrop-filter:blur(4px); }
    .events-reference .card-content { padding:22px 24px 28px; display:flex; flex-direction:column; flex-grow:1; }
    .events-reference .card-title { font-size:1.25rem; font-weight:700; margin-bottom:10px; color:var(--text-cream); line-height:1.3; }
    .events-reference .card-description { font-size:.98rem; color:#C7D8D2; line-height:1.6; margin-bottom:8px; flex-grow:1; }
    .events-reference .card-details { display:flex; flex-direction:column; gap:10px; font-size:.9rem; font-weight:500; color:var(--text-cream); border-top:1px solid rgba(255,255,255,.08); padding-top:18px; }
    .events-reference .detail-item { display:flex; align-items:center; gap:12px; }
    .events-reference .detail-icon { color:var(--accent-yellow); display:flex; align-items:center; justify-content:center; width:16px; height:16px; flex:0 0 16px; }
    .events-reference .detail-icon svg { width:100%; height:100%; stroke:currentColor; fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; }
    .events-reference .detail-text { color:#C7D8D2; }
    .events-reference .detail-text strong { color:var(--text-cream); font-weight:600; }
    .events-reference .read-more { align-self:flex-start; margin-top:4px; padding:0; border:0; background:transparent; color:#F4C542; font-size:.9rem; font-weight:700; text-decoration:underline; text-underline-offset:3px; cursor:pointer; }
    .events-reference .read-more:hover { color:#FFE08A; }
    .events-reference .pagination { display:flex; justify-content:center; gap:8px; margin:28px auto 0; }
    .events-reference .page-btn { min-width:40px; padding:10px 14px; border:1px solid rgba(212,160,23,.45); border-radius:100px; background:transparent; color:var(--text-muted); font-weight:600; cursor:pointer; }
    .events-reference .page-btn.active, .events-reference .page-btn:hover { background:var(--accent-yellow); color:var(--bg-deep-green); }
    @media (max-width:1100px) { .events-reference .events-container { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    .event-modal { position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:24px; background:rgba(0,0,0,.78); }
    .event-modal.is-open { display:flex; }
    .event-modal-card { position:relative; width:min(640px,100%); max-height:90vh; overflow:auto; border:1px solid var(--border-color); border-radius:20px; background:var(--bg-card); color:var(--text-cream); box-shadow:0 20px 60px rgba(0,0,0,.5); }
    .event-modal-image { display:none; width:100%; height:230px; object-fit:cover; }
    .event-modal-image.has-image { display:block; }
    .event-modal-content { padding:22px 24px 28px; }
    .event-modal-close { position:absolute; top:12px; right:12px; z-index:2; border:0; border-radius:50%; width:28px; height:28px; background:var(--accent-yellow); color:var(--bg-deep-green); font-size:1.2rem; line-height:1; cursor:pointer; }
    #event-modal-date { color:var(--accent-yellow); }
    #event-modal-title { color:var(--text-cream); }
    #event-modal-venue, #event-modal-description, #event-modal-fee, #event-modal-capacity { color:#C7D8D2; }
    @media (max-width:700px) { .events-hero { min-height:280px; } .events-reference { padding:40px 16px 48px; } .events-reference .filters { width:100%; justify-content:center; } .events-reference .filter-btn { padding:11px 18px; } .events-reference .events-container { grid-template-columns:1fr; } }
    @media (max-width:480px) { .events-reference .events-container { grid-template-columns:1fr; } }
</style>

<main>
    <section class="events-hero relative isolate overflow-hidden border-b border-white/10 bg-emerald-950" @if($heroImage) style="background-image:linear-gradient(90deg,rgb(3 17 12 / 90%),rgb(3 17 12 / 60%),rgb(3 17 12 / 35%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-30 md:py-40">
            <div class="max-w-3xl">
                <div class="text-sm font-bold uppercase tracking-widest text-amber-300">{{ $pageContent?->title ?: 'Events' }}</div>
                <h1 class="mt-4 text-5xl font-black tracking-tight text-white md:text-6xl">{{ $pageTitle }}</h1>
                @if($pageDescription)<div class="prose prose-invert mt-6 max-w-2xl text-lg leading-8 text-emerald-50/80">{!! $pageDescription !!}</div>@endif
            </div>
        </div>
    </section>

    <section class="events-reference" aria-label="Events">
        <div class="filters" role="group" aria-label="Filter events">
            @foreach(['all' => 'All Events', 'upcoming' => 'Upcoming', 'past' => 'Past'] as $filter => $label)
                <button class="filter-btn {{ $filter === 'all' ? 'active' : '' }}" type="button" data-event-filter="{{ $filter }}" aria-pressed="{{ $filter === 'all' ? 'true' : 'false' }}">{{ $label }}</button>
            @endforeach
        </div>

        <div class="events-container">
            @forelse($allEvents as $event)
                @php
                    $eventType = $event->starts_at?->isFuture() ? 'upcoming' : 'past';
                    $eventImage = $event->image('card', 'default', [], true);
                @endphp
                <article class="event-card" data-event-type="{{ $eventType }}" data-event-index="{{ $loop->index }}">
                    @if($eventImage)
                        <div class="card-image-wrapper">
                            <img src="{{ $eventImage }}" alt="{{ $event->title }}" loading="lazy">
                            <div class="date-overlay">{{ $event->starts_at?->format('D, d M Y') }} &middot; {{ $event->starts_at?->format('H:i') }}</div>
                        </div>
                    @endif
                    <div class="card-content">
                        @if(!$eventImage)<div class="date-overlay" style="position:static;width:max-content;margin-bottom:16px;">{{ $event->starts_at?->format('D, d M Y') }} &middot; {{ $event->starts_at?->format('H:i') }}</div>@endif
                            <h3 class="card-title"><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></h3>
                        <p class="card-description">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 180) }}</p>
                        <a
                            href="#"
                            class="read-more"
                            data-event-open
                            data-title="{{ e($event->title) }}"
                            data-date="{{ $event->starts_at?->format('l, d F Y · H:i') }}"
                            data-venue="{{ e($event->venue ?? '') }}"
                            data-description="{{ e(strip_tags($event->description ?? '')) }}"
                            data-fee="{{ $eventType === 'upcoming' && $event->fee !== null ? 'KES '.number_format((float) $event->fee, 0) : '' }}"
                            data-capacity="{{ $eventType === 'upcoming' && $event->capacity ? 'Up to '.number_format($event->capacity).' Participants' : '' }}"
                            data-image="{{ e($eventImage ?? '') }}"
                        >Read More</a>
                        <div class="card-details">
                            @if($event->venue)
                                <div class="detail-item"><span class="detail-icon"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span><span class="detail-text">Location: <strong>{{ $event->venue }}</strong></span></div>
                            @endif
                            @if($eventType === 'upcoming' && $event->fee !== null)
                                <div class="detail-item"><span class="detail-icon"><svg viewBox="0 0 24 24"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg></span><span class="detail-text">Fee: <strong>KES {{ number_format((float) $event->fee, 0) }}</strong></span></div>
                            @endif
                            @if($eventType === 'upcoming' && $event->capacity)
                                <div class="detail-item"><span class="detail-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span><span class="detail-text">Capacity: <strong>Up to {{ number_format($event->capacity) }} Participants</strong></span></div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-emerald-50/75">No public events are scheduled yet. <a class="font-bold text-amber-300" href="{{ route('book') }}">Plan a private session &rarr;</a></p>
            @endforelse
        </div>
        @if($allEvents->count() > 10)
            <nav id="event-pagination" class="pagination" aria-label="Event pages"></nav>
        @endif
    </section>
</main>

<div class="event-modal" id="event-modal" role="dialog" aria-modal="true" aria-labelledby="event-modal-title">
    <article class="event-modal-card">
        <img class="event-modal-image" id="event-modal-image" alt="">
        <div class="event-modal-content">
            <button type="button" class="event-modal-close" id="event-modal-close" aria-label="Close">&times;</button>
            <div class="text-sm font-bold uppercase tracking-widest text-amber-300" id="event-modal-date"></div>
            <h2 class="mt-3 text-3xl font-bold" id="event-modal-title"></h2>
            <p class="mt-3 text-emerald-50/70" id="event-modal-venue"></p>
            <p class="mt-6 whitespace-pre-line leading-7 text-emerald-50/80" id="event-modal-description"></p>
            <div class="mt-6 flex flex-wrap gap-4 text-sm font-semibold text-emerald-50/80">
                <span id="event-modal-fee"></span>
                <span id="event-modal-capacity"></span>
            </div>
        </div>
    </article>
</div>

<script>
    const eventPageSize = 10;
    let eventPage = 1;
    let eventFilter = 'all';
    const eventCards = Array.from(document.querySelectorAll('[data-event-type]'));
    const pagination = document.getElementById('event-pagination');
    const eventModal = document.getElementById('event-modal');
    const eventModalImage = document.getElementById('event-modal-image');

    function closeEventModal() {
        eventModal.classList.remove('is-open');
    }

    document.querySelectorAll('[data-event-open]').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById('event-modal-title').textContent = button.dataset.title;
            document.getElementById('event-modal-date').textContent = button.dataset.date;
            document.getElementById('event-modal-venue').textContent = button.dataset.venue ? `Location: ${button.dataset.venue}` : '';
            document.getElementById('event-modal-description').textContent = button.dataset.description;
            event.preventDefault();
            document.getElementById('event-modal-fee').textContent = button.dataset.fee ? `Fee: ${button.dataset.fee}` : '';
            document.getElementById('event-modal-capacity').textContent = button.dataset.capacity ? `Capacity: ${button.dataset.capacity}` : '';
            eventModalImage.src = button.dataset.image || '';
            eventModalImage.alt = button.dataset.title;
            eventModalImage.classList.toggle('has-image', Boolean(button.dataset.image));
            eventModal.classList.add('is-open');
        });
    });

    document.getElementById('event-modal-close').addEventListener('click', closeEventModal);
    eventModal.addEventListener('click', (event) => {
        if (event.target === eventModal) closeEventModal();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeEventModal();
    });

    function renderEvents() {
        const matchingCards = eventCards.filter((card) => eventFilter === 'all' || card.dataset.eventType === eventFilter);
        const totalPages = Math.max(1, Math.ceil(matchingCards.length / eventPageSize));
        eventPage = Math.min(eventPage, totalPages);
        const first = (eventPage - 1) * eventPageSize;
        const visibleCards = new Set(matchingCards.slice(first, first + eventPageSize));

        eventCards.forEach((card) => {
            card.hidden = !visibleCards.has(card);
        });

        if (pagination) {
            pagination.innerHTML = '';
            for (let page = 1; page <= totalPages; page += 1) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `page-btn${page === eventPage ? ' active' : ''}`;
                button.textContent = page;
                button.setAttribute('aria-label', `Go to event page ${page}`);
                button.setAttribute('aria-current', page === eventPage ? 'page' : 'false');
                button.addEventListener('click', () => {
                    eventPage = page;
                    renderEvents();
                });
                pagination.appendChild(button);
            }
        }
    }

    document.querySelectorAll('[data-event-filter]').forEach((button) => {
        button.addEventListener('click', () => {
            eventFilter = button.dataset.eventFilter;
            eventPage = 1;
            document.querySelectorAll('[data-event-filter]').forEach((item) => {
                const active = item === button;
                item.classList.toggle('active', active);
                item.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
            renderEvents();
        });
    });

    renderEvents();
</script>
@endsection
