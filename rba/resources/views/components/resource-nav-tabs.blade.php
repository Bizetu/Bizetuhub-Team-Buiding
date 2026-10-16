@props(['active' => ''])

@php
$colors = [ // RBA style variants; rba-yellow: #ffb901, rba-navy: #1f3b67
    'background' => '#1f3b67',
    'border' => '#1f3b67',
    'active' => [
        'text' => '#ffb901',
        'border' => '#ffb901',
    ],
    'inactive' => [
        'text' => 'white',
        'border' => 'transparent',
    ],
    'hover' => [
        'text' => '#ffb901',
        'border' => 'white',
    ],
];

$tabs = [
    [
        'key' => 'events',
        'label' => 'Events',
        'route' => route('events'),
        'title' => "Events",
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
    ],
    [
        'key' => 'research',
        'label' => 'Research and Publications',
        'route' => route('research'),
        'title' => "Research and Publications",
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
    ],
    [
        'key' => 'external-links',
        'label' => 'External Links',
        'route' => route('external-links'),
        'title' => "External Links",
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>',
    ],
    [
        'key' => 'videos',
        'label' => 'Videos',
        'route' => route('videos'),
        'title' => "Youtube Videos",
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
    ],
];
@endphp

<div class="bg-[{{ $colors['background'] }}] border-b border-[{{ $colors['border'] }}] w-full">
    <nav class="flex justify-center max-w-6xl mx-auto pl-4 md:pl-2 lg:pl-0">
        @foreach ($tabs as $tab)
            @php
                $isActive = $active === $tab['key'];
            @endphp
            <a
                href="{{ $tab['route'] }}"
                title="{{ $tab['title'] }}"
                class="group flex items-center gap-2 border-b-2 py-3 px-3 sm:px-6 text-base md:text-lg font-medium transition-all duration-200 flex-1 justify-center md:flex-none md:justify-start
                    {{ $isActive ? 'border-[#ffb901] text-[#ffb901] hover:text-[#ffb901]' : 'border-transparent text-white hover:text-[#ffb901] hover:border-white' }}"
            >
                <svg
                    class="w-5 h-5 flex-shrink-0 {{ $isActive ? 'text-[#ffb901] hover:text-[#ffb901]' : 'text-white group-hover:text-[#ffb901]' }} transition-colors duration-200"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    {!! $tab['icon'] !!}
                </svg>
                {{-- Hidden on mobile, visible from md up --}}
                <span class="hidden md:inline whitespace-nowrap">{{ $tab['label'] }}</span>
                {{-- Short label visible only on sm screens --}}
                <span class="inline md:hidden whitespace-nowrap text-base">{{ $tab['key'] === 'external-links' ? 'Links' : ($tab['key'] === 'research' ? 'Research' : ucfirst($tab['key'])) }}</span>
            </a>
        @endforeach
    </nav>
</div>
