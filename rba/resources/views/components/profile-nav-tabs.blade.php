{{-- @props(['active' => ''])

@php
$tabs = [
    [
        'key' => 'profileCourses',
        'label' => 'My Enrolled Modules',
        'route' => route('profile.courses'),
        'title' => "View a list of courses that you have enrolled into"
    ],
    [
        'key' => 'profileProgress',
        'label' => 'My Progress',
        'route' => route('profile.progress'),
        'title' => "View your progress both comprehensive and summarised"
    ],
    [
        'key' => 'trainingFeed',
        'label' => 'Training Feed',
        'route' => route('profile.trainingFeed'),
        'title' => "Here, you will be able to view feedback from users who have taken courses you have enrolled into"
    ],
];
@endphp

<div class="border-b border-gray-200 w-full">
    <nav class="flex justify-center gap-x-4 sm:gap-x-20 overflow-x-auto max-w-6xl mx-auto">
        @foreach ($tabs as $tab)
            <a href="{{ $tab['route'] }}" title="{{ $tab['title'] }}" class="border-b-2 py-2 px-1 text-sm md:text-lg font-medium whitespace-nowrap
                {{ $active === $tab['key'] ? 'border-[#1f3b67] text-[#1f3b67] hover:text-[#1f3b67]' : 'border-transparent text-[#1f3b67] hover:text-[#1f3b67] hover:font-bold hover:border-[#1f3b67]' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div> --}}

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
        'key' => 'profileCourses',
        'label' => 'My Enrolled Modules',
        'route' => route('profile.courses'),
        'title' => "View a list of courses that you have enrolled into",
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>', 
    ],
    [
        'key' => 'profileProgress',
        'label' => 'My Progress',
        'route' => route('profile.progress'),
        'title' => "View your progress both comprehensive and summarised",
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
    ],
    [
        'key' => 'trainingFeed',
        'label' => 'Training Feed',
        'route' => route('profile.trainingFeed'),
        'title' => "Here, you will be able to view feedback from users who have taken courses you have enrolled into",
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>',
    ],
];
@endphp

<div class="bg-[{{ $colors['background'] }}] border-b border-[{{ $colors['border'] }}] w-full">
    <nav class="flex justify-start md:justify-center max-w-6xl mx-auto overflow-x-auto px-4">
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
                <span class="hidden md:inline whitespace-nowrap">
                    {{ $tab['label'] }}
                </span>

                {{-- Short label visible only on small screens --}}
                <span class="inline md:hidden whitespace-nowrap text-base">
                    {{ match($tab['key']) {
                        'profileCourses' => 'My Enrolled Modules',
                        'profileProgress' => 'My Progress',
                        'trainingFeed' => 'Training Feed',
                        default => ucfirst($tab['key']),
                    } }}
                </span>
            </a>
        @endforeach
    </nav>
</div>