@if(isset($pageContent) && $pageContent?->image('hero', 'default', [], true))
    <div class="mx-auto max-w-7xl px-6 pt-10 {{ $class ?? '' }}">
        <img
            src="{{ $pageContent->image('hero', 'default', [], true) }}"
            alt="{{ $pageContent->imageAltText('hero') ?: ($pageContent->header_title ?: $pageContent->title) }}"
            class="aspect-[3/1] w-full rounded-3xl object-cover shadow-2xl ring-1 ring-white/10"
        >
    </div>
@endif
