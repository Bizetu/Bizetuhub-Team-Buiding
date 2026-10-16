@php($aboutPage = $block->blockable instanceof \App\Models\Page && $block->blockable->page_key === 'about')
<section class="mx-auto max-w-7xl px-6 py-10">
    <div class="overflow-hidden rounded-[2rem] bg-emerald-950 text-white px-7 py-12 text-center sm:px-12 md:py-16">
        @if($block->translatedInput('eyebrow'))
            <div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-300">{{ $block->translatedInput('eyebrow') }}</div>
        @endif
        <h2 class="mx-auto mt-4 max-w-3xl text-3xl font-black tracking-tight md:text-5xl">{{ $block->translatedInput('title') }}</h2>
        @if($block->translatedWysiwyg('text'))
            <div class="prose prose-invert text-emerald-50/80 mx-auto mt-5 max-w-2xl leading-7">{!! $block->translatedWysiwyg('text') !!}</div>
        @endif
        @if($block->translatedInput('button_label') && $block->input('button_url'))
            <a href="{{ $block->input('button_url') }}" class="mt-8 inline-flex rounded-full bg-amber-400 text-emerald-950 hover:bg-amber-300 px-7 py-4 font-extrabold transition">{{ $block->translatedInput('button_label') }} <span class="ml-2" aria-hidden="true">↗</span></a>
        @endif
    </div>
</section>
