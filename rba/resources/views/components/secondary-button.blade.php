@props([
    'type' => 'button',
    'href' => null,
    'tag'  => null,
    'icon' => null,
])

@php
    $element = $tag ?? ($href ? 'a' : 'button');
@endphp

<{{ $element }}
    @if ($element === 'a')    href="{{ $href }}"
    @elseif ($element === 'button') type="{{ $type }}"
    @endif
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full
                    font-bold text-lg text-gray-900 shadow transition hover:brightness-90
                    active:scale-95 cursor-pointer'
    ]) }}
    style="{{ $attributes->get('style', '') }} background-color: #FFB901;"
>
    @if ($icon ?? false)
        {{ $icon }}
    @endif

    {{ $slot }}
</{{ $element }}>