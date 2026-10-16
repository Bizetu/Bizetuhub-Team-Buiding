@extends('layouts.site', ['title' => 'People. Culture. Growth.'])
@section('content')
<main>
    <section class="relative isolate overflow-hidden bg-emerald-950 text-white" aria-label="Featured">
        <div data-home-slider data-autoplay="{{ $homeSlides->count() > 1 ? 'true' : 'false' }}" class="relative">
            @forelse($homeSlides as $index => $slide)
                @php
                    $background = $slide->image('background', 'default', [], true);
                    $background = $background ?: $slide->image_url;
                    $animationVideo = $slide->file('animation') ?: $slide->video_url;
                @endphp
                <article data-home-slide class="relative isolate flex min-h-[500px] items-center overflow-hidden sm:min-h-[520px] {{ $index === 0 ? '' : 'hidden' }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}" aria-roledescription="slide" aria-label="{{ $index + 1 }} of {{ $homeSlides->count() }}">
                    @if($animationVideo)
                        <video src="{{ $animationVideo }}" @if($background) poster="{{ $background }}" @endif class="absolute inset-0 -z-20 h-full w-full object-cover" muted loop playsinline preload="metadata" aria-hidden="true" @if($index > 0) tabindex="-1" @endif></video>
                    @elseif($background)
                        <img src="{{ $background }}" alt="{{ $slide->imageAltText('background') }}" class="absolute inset-0 -z-20 h-full w-full object-cover" @if($index > 0) loading="lazy" @else fetchpriority="high" @endif>
                    @else
                        <div class="absolute inset-0 -z-20" aria-hidden="true" style="background: radial-gradient(ellipse at {{ $index % 2 ? '82% 25%' : '76% 78%' }}, rgba(37,128,84,.64), transparent 38%), radial-gradient(ellipse at 8% 85%, rgba(7,91,234,.2), transparent 42%), #071f17;"></div>
                    @endif
                    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/85 via-emerald-950/60 to-transparent" aria-hidden="true"></div>
                    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-emerald-950/25 via-transparent to-emerald-950/10" aria-hidden="true"></div>
                    <div class="mx-auto w-full max-w-7xl px-6 py-14 sm:px-8 sm:py-16">
                        <div class="max-w-[620px]">
                            <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.2em] text-amber-300 backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>{{ $slide->eyebrow ?: 'Nairobi · Kenya' }}
                            </div>
                            <h1 class="mt-5 max-w-[600px] text-5xl font-black leading-[1.02] tracking-tight sm:mt-6 sm:text-6xl md:text-7xl">
                                @if(\Illuminate\Support\Str::endsWith($slide->title, 'Become.'))
                                    {{ \Illuminate\Support\Str::beforeLast($slide->title, 'Become.') }}<span class="block text-amber-400">Become.</span>
                                @else
                                    {{ $slide->title }}
                                @endif
                            </h1>
                            @if($slide->description)
                                <p class="mt-5 max-w-[650px] text-base leading-7 text-white/85 sm:mt-6 sm:text-lg sm:leading-8">{{ $slide->description }}</p>
                            @endif
                            <div class="mt-7 flex flex-wrap gap-3.5 sm:mt-8">
                                @if($slide->button_label && $slide->button_url)
                                    <a href="{{ $slide->button_url }}" class="inline-flex items-center rounded-full bg-amber-400 px-6 py-3.5 text-sm font-extrabold text-emerald-950 shadow-lg shadow-black/20 transition hover:-translate-y-0.5 hover:bg-amber-300 sm:px-7 sm:py-4">{{ $slide->button_label }} <span class="ml-2" aria-hidden="true">↗</span></a>
                                @else
                                    <a href="{{ route('book') }}" class="inline-flex items-center rounded-full bg-amber-400 px-6 py-3.5 text-sm font-extrabold text-emerald-950 shadow-lg shadow-black/20 transition hover:-translate-y-0.5 hover:bg-amber-300 sm:px-7 sm:py-4">Start a conversation <span class="ml-2" aria-hidden="true">↗</span></a>
                                @endif
                                <a href="{{ route('services') }}" class="inline-flex items-center rounded-full border border-white/30 bg-white/5 px-6 py-3.5 text-sm font-bold text-white backdrop-blur transition hover:border-white/60 hover:bg-white/10 sm:px-7 sm:py-4">Explore what we do <span class="ml-2" aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <article data-home-slide class="relative isolate flex min-h-[500px] items-center overflow-hidden sm:min-h-[520px]" aria-roledescription="slide" aria-label="1 of 1">
                    <div class="absolute inset-0 -z-20" aria-hidden="true" style="background: radial-gradient(ellipse at 82% 24%, rgba(37,128,84,.6), transparent 38%), radial-gradient(ellipse at 8% 85%, rgba(7,91,234,.2), transparent 42%), #071f17;"></div>
                    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/80 via-emerald-950/60 to-emerald-950/20" aria-hidden="true"></div>
                    <div class="mx-auto w-full max-w-7xl px-6 py-14 sm:px-8 sm:py-16">
                        <div class="max-w-[620px]">
                            <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.2em] text-amber-300"><span class="h-2 w-2 rounded-full bg-amber-400"></span>Nairobi · Kenya</div>
                            <h1 class="mt-5 text-5xl font-black leading-[1.02] tracking-tight sm:mt-6 sm:text-6xl md:text-7xl">Build. Belong. <span class="block text-amber-400">Become.</span></h1>
                            <p class="mt-5 max-w-[650px] text-base leading-7 text-white/85 sm:mt-6 sm:text-lg sm:leading-8">Bizetu Hub helps organisations build stronger teams and professionals grow stronger careers.</p>
                            <div class="mt-7 flex flex-wrap gap-3.5 sm:mt-8">
                                <a href="{{ route('book') }}" class="inline-flex items-center rounded-full bg-amber-400 px-6 py-3.5 text-sm font-extrabold text-emerald-950 shadow-lg shadow-black/20 transition hover:-translate-y-0.5 hover:bg-amber-300 sm:px-7 sm:py-4">Start a conversation <span class="ml-2" aria-hidden="true">↗</span></a>
                                <a href="{{ route('services') }}" class="inline-flex items-center rounded-full border border-white/30 bg-white/5 px-6 py-3.5 text-sm font-bold text-white backdrop-blur transition hover:bg-white/10 sm:px-7 sm:py-4">Explore what we do <span class="ml-2" aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    </div>
                </article>
            @endforelse

            @if($homeSlides->count() > 1)
                @php
                    $hasAnimation = $homeSlides->contains(
                        fn ($slide) => filled($slide->file('animation')) || filled($slide->video_url)
                    );
                @endphp
                <div class="pointer-events-none absolute inset-x-0 bottom-5 z-20 px-6">
                    <div class="relative mx-auto flex max-w-7xl items-center justify-center">
                        <div class="pointer-events-auto flex items-center gap-2" aria-label="Choose a featured slide">
                            @foreach($homeSlides as $index => $slide)
                                <button type="button" data-slide-to="{{ $index }}" aria-label="Show slide {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}" class="h-2.5 rounded-full border border-amber-300/80 transition-all {{ $index === 0 ? 'w-8 bg-amber-400' : 'w-2.5 bg-transparent' }}"></button>
                            @endforeach
                        </div>
                        <div class="pointer-events-auto absolute right-0 flex items-center gap-3">
                            @if($hasAnimation)
                                <button type="button" data-slide-mute aria-label="Unmute animation" class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-emerald-950 shadow-md transition hover:bg-amber-300" title="Mute or unmute animation">
                                    <svg data-icon-muted viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 5 6 9H3v6h3l5 4V5Z"/><path d="m17 9 4 6m0-6-4 6"/></svg>
                                    <svg data-icon-unmuted viewBox="0 0 24 24" class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 5 6 9H3v6h3l5 4V5Z"/><path d="M15.5 8.5a5 5 0 0 1 0 7m3-10a9 9 0 0 1 0 13"/></svg>
                                </button>
                            @endif
                            <button type="button" data-slide-toggle-play aria-label="Pause slideshow" aria-pressed="false" class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-emerald-950 shadow-md transition hover:bg-amber-300" title="Pause or resume slideshow">
                                <svg data-icon-pause viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true"><path d="M7 5h4v14H7zm6 0h4v14h-4z"/></svg>
                                <svg data-icon-play viewBox="0 0 24 24" class="hidden h-5 w-5" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                            </button>
                            <button type="button" data-slide-previous aria-label="Previous slide" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/30 text-lg transition hover:border-amber-300 hover:bg-white/10">←</button>
                            <button type="button" data-slide-next aria-label="Next slide" class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-lg text-emerald-950 transition hover:bg-amber-300">→</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @php
        $homeBlocks = $homePage?->blocks ?? collect();
        $legacyHomeBlocks = $homeBlocks->where('type', 'content_section');
        $homeBlock = fn (string $type): mixed => $homeBlocks->firstWhere('type', $type);
        $homeSection = fn (string $section): mixed => $homeBlocks->first(
            fn ($block): bool => $block->type === 'home_section_heading'
                && strtolower((string) $block->input('section')) === $section
        );
        $intro = $homeBlock('home_intro');
        $process = $homeBlock('home_process');
        $servicesHeading = $homeSection('services');
        $eventsHeading = $homeSection('events');
        $testimonialsHeading = $homeSection('testimonials');
        $cta = $homeBlock('call_to_action');
    @endphp

    @foreach($legacyHomeBlocks as $legacyBlock)
        @include('site.blocks.content_section', ['block' => $legacyBlock])
    @endforeach

    @if($legacyHomeBlocks->isEmpty() && $intro)
        <section class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="mx-auto max-w-3xl text-center">
                @if($intro->translatedInput('eyebrow'))
                    <div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">{{ $intro->translatedInput('eyebrow') }}</div>
                @endif
                @if($intro->translatedInput('title'))
                    <h2 class="mt-3 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">{{ $intro->translatedInput('title') }}</h2>
                @endif
                @if($intro->translatedWysiwyg('text'))
                    <div class="prose prose-slate mx-auto mt-5 max-w-2xl text-lg leading-8">{!! $intro->translatedWysiwyg('text') !!}</div>
                @endif
            </div>
        </section>
    @endif

    @if($process)
        <section class="bg-emerald-900 text-white" aria-label="{{ $process->translatedInput('title') }}">
            <div class="mx-auto max-w-7xl px-6 py-16 md:py-20">
                @if($process->translatedInput('eyebrow') || $process->translatedInput('title'))
                    <div class="mb-8 text-center">
                        @if($process->translatedInput('eyebrow'))<div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-300">{{ $process->translatedInput('eyebrow') }}</div>@endif
                        @if($process->translatedInput('title'))<h2 class="mt-3 text-3xl font-black">{{ $process->translatedInput('title') }}</h2>@endif
                    </div>
                @endif
                @php
                    $processSteps = $process->children->where('type', 'home_process_step');
                    if ($processSteps->isEmpty()) {
                        $processSteps = collect([1, 2, 3])->map(function ($number) use ($process) {
                            return (object) [
                                'title' => $process->translatedInput("step_{$number}_title"),
                                'text' => $process->translatedWysiwyg("step_{$number}_text"),
                            ];
                        })->filter(fn ($step) => $step->title || $step->text);
                    } else {
                        $processSteps = $processSteps->map(fn ($step) => (object) [
                            'title' => $step->translatedInput('title'),
                            'text' => $step->translatedWysiwyg('text'),
                        ]);
                    }
                @endphp
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($processSteps as $step)
                        <div class="border-l-2 border-amber-400 pl-5">
                            @if($step->title)<div class="text-2xl font-extrabold">{{ $step->title }}</div>@endif
                            @if($step->text)<div class="prose prose-invert mt-2 text-sm leading-6 text-emerald-100/75">{!! $step->text !!}</div>@endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($servicesHeading && $services->isNotEmpty())
        <section class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="flex items-end justify-between gap-6">
                <div>
                    @if($servicesHeading->translatedInput('eyebrow'))<div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">{{ $servicesHeading->translatedInput('eyebrow') }}</div>@endif
                    @if($servicesHeading->translatedInput('title'))<h2 class="mt-3 text-4xl font-black tracking-tight text-slate-950">{{ $servicesHeading->translatedInput('title') }}</h2>@endif
                    @if($servicesHeading->translatedWysiwyg('text'))<div class="prose prose-slate mt-3">{!! $servicesHeading->translatedWysiwyg('text') !!}</div>@endif
                </div>
                <a href="{{ route('services') }}" class="hidden text-sm font-extrabold text-emerald-800 md:block">View all services <span aria-hidden="true">→</span></a>
            </div>
            <div class="mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3" aria-label="Featured services">
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
                    <article class="service-glass-card group relative flex min-h-[400px] flex-col justify-between overflow-hidden rounded-3xl">
                        <img src="{{ $cover ?: $fallbackCover }}" alt="{{ $service->imageAltText('cover') ?: $service->title }}" class="service-card-image absolute inset-0 h-full w-full object-cover" loading="lazy">
                        <div class="service-card-shade absolute inset-0" aria-hidden="true"></div>

                        <div class="relative z-10 p-6" aria-hidden="true">
                            <div class="h-12 w-12"></div>
                        </div>

                        <div class="relative z-10 p-6 pt-0">
                            <h3 class="text-2xl font-bold text-white transition-colors group-hover:text-amber-300">{{ $service->title }}</h3>
                            @if($service->excerpt)
                                <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $service->excerpt }}</p>
                            @endif
                            <a href="{{ route('services.show', $service) }}" class="service-card-link btn-shimmer mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-extrabold">
                                Explore programme <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if($eventsHeading && $events->isNotEmpty())
        <section class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="flex items-end justify-between gap-6">
                <div>
                    @if($eventsHeading->translatedInput('eyebrow'))<div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">{{ $eventsHeading->translatedInput('eyebrow') }}</div>@endif
                    @if($eventsHeading->translatedInput('title'))<h2 class="mt-3 text-4xl font-black tracking-tight">{{ $eventsHeading->translatedInput('title') }}</h2>@endif
                    @if($eventsHeading->translatedWysiwyg('text'))<div class="prose prose-slate mt-3">{!! $eventsHeading->translatedWysiwyg('text') !!}</div>@endif
                </div>
                <a class="hidden text-sm font-extrabold text-emerald-800 md:block" href="{{ route('events') }}">View all events <span aria-hidden="true">→</span></a>
            </div>
            <div class="mt-10 grid gap-8 md:grid-cols-3">
                @forelse($events as $event)
                    @php($eventImage = $event->image('card', 'default', [], true))
                    <article class="flex h-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-[#112B24] text-white shadow-xl shadow-black/20">
                        @if($eventImage)
                            <div class="relative h-48 w-full">
                                <img src="{{ $eventImage }}" alt="{{ $event->title }}" class="h-full w-full object-cover brightness-90 contrast-110" loading="lazy">
                                <div class="absolute bottom-3 left-3 rounded-full border border-amber-300/30 bg-[#0A1F1A]/95 px-4 py-2 text-xs font-bold uppercase tracking-wide text-amber-300">{{ $event->starts_at?->format('D, d M Y') }} · {{ $event->starts_at?->format('H:i') }}</div>
                            </div>
                        @else
                            <div class="px-6 pt-6"><div class="inline-flex rounded-full border border-amber-300/30 bg-emerald-950/70 px-4 py-2 text-xs font-bold uppercase tracking-wide text-amber-300">{{ $event->starts_at?->format('D, d M Y') }} · {{ $event->starts_at?->format('H:i') }}</div></div>
                        @endif
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="text-xl font-bold leading-snug"><a href="{{ route('events.show', $event) }}" class="hover:text-amber-300">{{ $event->title }}</a></h3>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-[#C7D8D2]">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 180) }}</p>
                            <a href="{{ route('events.show', $event) }}" class="mt-4 self-start text-sm font-bold text-amber-300 underline underline-offset-4">Read more</a>
                                <div class="mt-4 flex flex-col gap-3 border-t border-white/10 pt-4 text-sm text-[#C7D8D2]">
                                @if($event->venue)<div>Location: <strong class="font-semibold text-white">{{ $event->venue }}</strong></div>@endif
                                @if($event->fee !== null)<div>Fee: <strong class="font-semibold text-white">KES {{ number_format((float) $event->fee, 0) }}</strong></div>@endif
                                @if($event->capacity)<div>Capacity: <strong class="font-semibold text-white">Up to {{ number_format($event->capacity) }} Participants</strong></div>@endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-slate-600">New public events are coming soon.</p>
                @endforelse
            </div>
        </section>
    @endif

    @if($testimonialsHeading && $testimonials->isNotEmpty())
        <section class="border-y border-slate-200 bg-white py-20">
            <div class="mx-auto max-w-7xl px-6">
                @if($testimonialsHeading->translatedInput('eyebrow'))<div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">{{ $testimonialsHeading->translatedInput('eyebrow') }}</div>@endif
                @if($testimonialsHeading->translatedInput('title'))<h2 class="mt-3 text-4xl font-black tracking-tight">{{ $testimonialsHeading->translatedInput('title') }}</h2>@endif
                @if($testimonialsHeading->translatedWysiwyg('text'))<div class="prose prose-slate mt-3">{!! $testimonialsHeading->translatedWysiwyg('text') !!}</div>@endif
                <div class="mt-10 grid gap-8 md:grid-cols-3">
                    @foreach($testimonials as $testimonial)
                        <figure class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div class="text-amber-500" aria-label="{{ $testimonial->rating }} out of 5 stars">{{ str_repeat('★', $testimonial->rating) }}</div>
                            <blockquote class="mt-4 text-lg leading-7 text-slate-700">“{{ strip_tags($testimonial->description) }}”</blockquote>
                            <figcaption class="mt-7 border-t border-slate-200 pt-5 text-sm font-bold">{{ $testimonial->client ?: $testimonial->title }}</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($legacyHomeBlocks->isEmpty() && $cta)
        <section class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="overflow-hidden rounded-[2rem] bg-emerald-950 px-7 py-14 text-center text-white sm:px-12 md:py-16">
                @if($cta->translatedInput('eyebrow'))<div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-300">{{ $cta->translatedInput('eyebrow') }}</div>@endif
                @if($cta->translatedInput('title'))<h2 class="mx-auto mt-4 max-w-3xl text-4xl font-black tracking-tight md:text-5xl">{{ $cta->translatedInput('title') }}</h2>@endif
                @if($cta->translatedWysiwyg('text'))<div class="prose prose-invert mx-auto mt-5 max-w-2xl leading-7 text-emerald-50/75">{!! $cta->translatedWysiwyg('text') !!}</div>@endif
                @if($cta->translatedInput('button_label') && $cta->input('button_url'))
                    <a href="{{ $cta->input('button_url') }}" class="mt-8 inline-flex rounded-full bg-amber-400 px-7 py-4 font-extrabold text-emerald-950 transition hover:bg-amber-300">{{ $cta->translatedInput('button_label') }} <span class="ml-2" aria-hidden="true">↗</span></a>
                @endif
            </div>
        </section>
    @endif
</main>
@endsection
