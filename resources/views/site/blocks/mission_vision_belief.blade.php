@php
    $statements = [
        [
            'title' => $block->translatedInput('vision_title') ?: 'Our Vision',
            'text' => $block->translatedWysiwyg('vision_text'),
            'wide' => false,
        ],
        [
            'title' => $block->translatedInput('mission_title') ?: 'Our Mission',
            'text' => $block->translatedWysiwyg('mission_text'),
            'wide' => false,
        ],
        [
            'title' => $block->translatedInput('belief_title') ?: 'Our Belief',
            'text' => $block->translatedWysiwyg('belief_text'),
            'wide' => false,
        ],
    ];
@endphp

@if(collect($statements)->contains(fn ($statement) => filled($statement['text'])))
    <section class="relative z-10 mx-auto max-w-7xl px-6 pb-16 pt-8 md:pb-20 md:pt-10" aria-label="Our vision, mission and belief">
        <div class="grid gap-5 md:grid-cols-3 md:gap-6">
            @foreach($statements as $index => $statement)
                @if(filled($statement['text']))
                    <article class="group rounded-3xl border border-emerald-400/15 bg-[#103f2d] p-7 shadow-xl transition duration-300 hover:-translate-y-1 hover:border-amber-300/40 sm:p-8">
                        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-400/15 text-amber-300">
                            @if($index === 0)
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                            @elseif($index === 1)
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 12 2 2 4-4m5-3-8-4-8 4v5c0 5 3.4 8 8 10 4.6-2 8-5 8-10V7Z"/></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 20-7.2-7.1a4.5 4.5 0 0 1 6.4-6.4L12 7.3l.8-.8a4.5 4.5 0 0 1 6.4 6.4L12 20Z"/></svg>
                            @endif
                        </div>
                        <h2 class="text-xl font-black text-white">{{ $statement['title'] }}</h2>
                        <div class="prose prose-invert mt-3 max-w-none text-sm leading-6 text-emerald-50/75">{!! $statement['text'] !!}</div>
                    </article>
                @endif
            @endforeach
        </div>
    </section>
@endif
