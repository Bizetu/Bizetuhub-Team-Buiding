<div class="service-detail-content">
    <div class="grid gap-6 lg:grid-cols-12">
        <section class="service-detail-panel flex flex-col justify-between rounded-3xl p-7 sm:p-10 lg:col-span-7" aria-labelledby="service-title-{{ $service->slug }}">
            <div>
                <div class="flex items-start justify-between gap-4">
                    <div class="service-icon-tile flex h-14 w-14 items-center justify-center rounded-2xl text-2xl font-black text-amber-300" aria-hidden="true">{{ $service->icon ?: '✦' }}</div>
                    <span class="rounded-full border border-amber-300/40 bg-amber-300/20 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-amber-200">
                        {{ $service->badge ?: ($service->featured ? 'Featured' : 'Programme') }}
                    </span>
                </div>

                <h1 id="service-title-{{ $service->slug }}" tabindex="-1" class="mt-7 text-3xl font-extrabold tracking-tight text-white sm:text-5xl">{{ $service->title }}</h1>
                @if($service->excerpt)
                    <p class="mt-4 text-xl font-semibold leading-relaxed text-amber-300">{{ $service->excerpt }}</p>
                @endif
                @if($service->description)
                    <p class="mt-5 whitespace-pre-line text-base leading-8 text-slate-300">{{ strip_tags($service->description) }}</p>
                @endif

                <div class="mt-8 border-t border-white/10 pt-7">
                    <h2 class="mb-4 text-xs font-extrabold uppercase tracking-[0.16em] text-slate-400">Programme highlights</h2>
                    <ul class="grid gap-3 text-sm leading-6 text-slate-200 sm:grid-cols-2">
                        @forelse($service->highlights_list as $highlight)
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 text-emerald-300" aria-hidden="true">✓</span>
                                <span>{{ $highlight }}</span>
                            </li>
                        @empty
                            <li class="flex items-start gap-3"><span class="mt-0.5 text-emerald-300" aria-hidden="true">✓</span><span>Activities tailored to your team’s goals</span></li>
                            <li class="flex items-start gap-3"><span class="mt-0.5 text-emerald-300" aria-hidden="true">✓</span><span>Facilitated reflection and practical takeaways</span></li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-white/10 pt-7">
                <button
                    type="button"
                    class="service-card-link btn-shimmer inline-flex items-center gap-2 rounded-xl px-6 py-3.5 text-sm font-extrabold"
                    data-service-proposal
                    data-programme-title="{{ $service->title }}"
                    data-booking-url="{{ route('book', ['service_id' => $service->id]) }}"
                >
                    Request this programme <span aria-hidden="true">↗</span>
                </button>
                <a href="{{ route('contact') }}" class="service-secondary-link inline-flex items-center rounded-xl px-6 py-3.5 text-sm font-bold">Ask a question</a>
            </div>
        </section>

        <aside class="flex flex-col gap-5 lg:col-span-5" aria-label="Programme overview">
            @php
                $detailCover = $service->image('cover', 'default', [], true);
                $fallbackCovers = [
                    'https://images.unsplash.com/photo-1517824806704-9040b037703b?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=1200&q=80',
                ];
                $serviceIndex = $services->search(fn ($candidate) => $candidate->is($service));
                $fallbackCover = $fallbackCovers[($serviceIndex === false ? 0 : $serviceIndex) % count($fallbackCovers)];
            @endphp
            <div class="service-detail-image relative min-h-[320px] flex-1 overflow-hidden rounded-3xl">
                <img src="{{ $detailCover ?: $fallbackCover }}" alt="{{ $service->imageAltText('cover') ?: $service->title }}" class="absolute inset-0 h-full w-full object-cover">
                <div class="service-detail-image-shade absolute inset-0" aria-hidden="true"></div>
                @if($service->duration || $service->group_size)
                    <dl class="service-stats-panel absolute inset-x-4 bottom-4 grid grid-cols-2 gap-4 rounded-2xl p-5 sm:inset-x-6 sm:bottom-6">
                        @if($service->duration)
                            <div>
                                <dt class="text-[10px] font-extrabold uppercase tracking-[0.15em] text-white/55">Duration</dt>
                                <dd class="mt-1.5 text-base font-bold text-white">{{ $service->duration }}</dd>
                            </div>
                        @endif
                        @if($service->group_size)
                            <div class="border-l border-white/15 pl-4">
                                <dt class="text-[10px] font-extrabold uppercase tracking-[0.15em] text-white/55">Group size</dt>
                                <dd class="mt-1.5 text-base font-bold text-amber-200">{{ $service->group_size }}</dd>
                            </div>
                        @endif
                    </dl>
                @endif
            </div>

            @if($services->count() > 1)
                <nav class="service-switcher rounded-2xl p-4" aria-label="Switch programme">
                    <p class="mb-3 px-1 text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">Switch programme</p>
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach($services as $otherService)
                            <a
                                href="{{ route('services.show', $otherService) }}"
                                @if($interactive) data-service-open="{{ $otherService->slug }}" @endif
                                @if($otherService->is($service)) aria-current="page" @endif
                                class="service-switcher-link shrink-0 rounded-lg px-3 py-2 text-xs font-bold"
                            >{{ \Illuminate\Support\Str::limit($otherService->title, 22) }}</a>
                        @endforeach
                    </div>
                </nav>
            @else
                <div class="service-detail-note rounded-2xl p-5">
                    <div class="text-xs font-extrabold uppercase tracking-[0.15em] text-emerald-100/55">Designed around your team</div>
                    <p class="mt-2 text-sm leading-6 text-emerald-50/80">We’ll tailor the format, facilitation and activities to your goals and the people taking part.</p>
                </div>
            @endif
        </aside>
    </div>

    <section class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-3" aria-label="What every programme includes">
        <article class="service-outcome-card rounded-2xl p-6">
            <span class="service-outcome-icon service-outcome-icon--green" aria-hidden="true">✓</span>
            <h2 class="mt-4 text-lg font-bold text-white">Safe &amp; inclusive space</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-300">Activities are planned with safety, accessibility and psychological trust at their core.</p>
        </article>
        <article class="service-outcome-card rounded-2xl p-6">
            <span class="service-outcome-icon service-outcome-icon--amber" aria-hidden="true">◎</span>
            <h2 class="mt-4 text-lg font-bold text-white">Facilitated debriefs</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-300">Structured reflection helps turn shared experiences into useful workplace habits.</p>
        </article>
        <article class="service-outcome-card rounded-2xl p-6">
            <span class="service-outcome-icon service-outcome-icon--green" aria-hidden="true">⌖</span>
            <h2 class="mt-4 text-lg font-bold text-white">Flexible locations</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-300">Choose a nature-based venue that works for your group, timing and goals.</p>
        </article>
    </section>
</div>
