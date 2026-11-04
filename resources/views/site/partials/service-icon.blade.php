@php
    $serviceTitle = strtolower((string) $service->title);
    $iconType = str_contains($serviceTitle, 'leadership') || str_contains($serviceTitle, 'leader')
        ? 'leadership'
        : (str_contains($serviceTitle, 'creative') || str_contains($serviceTitle, 'problem') ? 'creative' : 'connection');
    $iconClass = $class ?? 'h-8 w-8';
@endphp

@switch($iconType)
    @case('leadership')
        <svg viewBox="0 0 24 24" class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 20V4" />
            <path d="m6 10 6-6 6 6" />
            <path d="M5 20h14" />
            <path d="M8 20v-5h8v5" />
        </svg>
        @break
    @case('creative')
        <svg viewBox="0 0 24 24" class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 18h6" />
            <path d="M10 22h4" />
            <path d="M8.5 14.5a7 7 0 1 1 7 0c-.9.6-1.5 1.5-1.5 2.5h-4c0-1-.6-1.9-1.5-2.5Z" />
            <path d="M12 2v1" />
            <path d="m4.9 4.9.7.7" />
            <path d="m19.1 4.9-.7.7" />
        </svg>
        @break
    @default
        <svg viewBox="0 0 24 24" class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="8" cy="8" r="3" />
            <circle cx="16" cy="8" r="3" />
            <path d="M2.5 19a5.5 5.5 0 0 1 11 0" />
            <path d="M10.5 19a5.5 5.5 0 0 1 11 0" />
        </svg>
@endswitch
