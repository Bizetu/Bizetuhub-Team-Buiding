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
        'key' => 'pillars',
        'label' => 'ESG Pillars',
        'route' => route('pillars'),
        'title' => "Pillars",
        'icon' => file_get_contents(public_path('images/defaults/esg-icon.svg')),    ],
    [
        'key' => 'esg-guidelines',
        'label' => 'ESG Guidelines',
        'route' => route('esg-guidelines'),
        'title' => "ESG Guidelines",
        'icon' => file_get_contents(public_path('images/defaults/pillars-icon.svg')),    ],
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
                <span class="inline md:hidden whitespace-nowrap text-base">{{ $tab['key'] === 'esg-guidelines' ? 'ESG Guidelines' : ($tab['key'] === 'pillars' ? 'Pillars' : ucfirst($tab['key'])) }}</span>
            </a>
        @endforeach
    </nav>
</div>
