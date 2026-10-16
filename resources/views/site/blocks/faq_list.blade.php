<section class="mx-auto max-w-5xl px-6 py-12">
    <div class="max-w-3xl">
        @if($block->translatedInput('eyebrow'))
            <div class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-600">{{ $block->translatedInput('eyebrow') }}</div>
        @endif
        @if($block->translatedInput('title'))
            <h2 class="mt-3 text-3xl font-black tracking-tight md:text-4xl">{{ $block->translatedInput('title') }}</h2>
        @endif
    </div>
    <div class="mt-8 divide-y divide-slate-200 rounded-3xl bg-white px-7 shadow-sm ring-1 ring-slate-200">
        @foreach($block->children as $item)
            @if($item->type === 'faq_item')
                @include('site.blocks.faq_item', ['block' => $item])
            @endif
        @endforeach
    </div>
</section>
