<section class="mx-auto max-w-7xl px-6 py-16 md:py-20">
    <div class="{{ $block->hasImage('image', 'desktop') ? 'grid items-center gap-10 md:grid-cols-2' : 'max-w-3xl' }}">
        @if($block->hasImage('image', 'desktop'))
            <img src="{{ $block->image('image', 'desktop', [], true) }}" alt="{{ $block->imageAltText('image') }}" class="w-full rounded-3xl object-cover">
        @endif
        <div>
        @if($block->translatedInput('eyebrow'))
            <div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">{{ $block->translatedInput('eyebrow') }}</div>
        @endif
        @if($block->translatedInput('title'))
            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 md:text-4xl">{{ $block->translatedInput('title') }}</h2>
        @endif
        @if($block->translatedWysiwyg('text'))
            <div class="prose prose-slate mt-5 max-w-none leading-8">{!! $block->translatedWysiwyg('text') !!}</div>
        @endif
        </div>
    </div>
</section>
