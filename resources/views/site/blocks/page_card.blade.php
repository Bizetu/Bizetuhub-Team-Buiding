@php($background = $block->image('background', 'default', [], true))
<article class="relative isolate h-full overflow-hidden rounded-3xl border border-slate-200 bg-white p-7 shadow-sm">
    @if($background)
        <img src="{{ $background }}" alt="" aria-hidden="true" class="absolute inset-0 z-0 h-full w-full object-cover" loading="lazy" />
        <div class="absolute inset-0 z-[1] bg-gradient-to-br from-black/80 via-black/65 to-black/50"></div>
    @endif
    <h3 class="relative z-10 text-xl font-extrabold {{ $background ? 'text-white drop-shadow-[0_1px_3px_rgba(0,0,0,0.95)]' : 'text-slate-950' }}">{{ $block->translatedInput('title') }}</h3>
    @if($block->translatedWysiwyg('text'))
        <div class="prose relative z-10 mt-3 text-sm leading-6 {{ $background ? 'prose-invert text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.95)]' : 'prose-slate' }}">{!! $block->translatedWysiwyg('text') !!}</div>
    @endif
    @if($block->translatedInput('link_label') && $block->input('link_url'))
        <a href="{{ $block->input('link_url') }}" class="relative z-10 mt-6 inline-flex items-center gap-2 text-sm font-extrabold {{ $background ? 'text-white drop-shadow-[0_1px_3px_rgba(0,0,0,0.95)]' : 'text-emerald-800' }}">{{ $block->translatedInput('link_label') }} <span aria-hidden="true">→</span></a>
    @endif
</article>
