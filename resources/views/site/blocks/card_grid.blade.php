<section class="mx-auto max-w-7xl px-6 py-16 md:py-20">
    <div class="mx-auto max-w-3xl text-center">
        @if($block->translatedInput('eyebrow'))
            <div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">{{ $block->translatedInput('eyebrow') }}</div>
        @endif
        @if($block->translatedInput('title'))
            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 md:text-4xl">{{ $block->translatedInput('title') }}</h2>
        @endif
        @if($block->translatedWysiwyg('text'))
            <div class="prose prose-slate mx-auto mt-5 max-w-2xl">{!! $block->translatedWysiwyg('text') !!}</div>
        @endif
    </div>
    <div class="mt-10 grid auto-rows-fr gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($block->children as $item)
            @if($item->type === 'page_card')
                @include('site.blocks.page_card', ['block' => $item])
            @endif
        @endforeach
    </div>
</section>
