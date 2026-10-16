@props(['active' => ''])

@php
$colors = [
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
        'key' => 'forums',
        'label' => 'Forums',
        'route' => route('forums'),
        'title' => 'Discussion forums',
    ],
    [
        'key' => 'discover',
        'label' => 'Discover',
        'route' => route('community.index'),
        'title' => "Communities",
    ],
    [
        'key' => 'following',
        'label' => 'Following',
        'route' => route('community.following'),
        'title' => 'Following users',
    ],
    [
        'key' => 'followers',
        'label' => 'Followers',
        'route' => route('community.followers'),
        'title' => 'Your followers',
    ],
    [
        'key' => 'requests',
        'label' => 'Requests',
        'route' => route('community.requests'),
        'title' => 'Follow requests',
    ],
    [
        'key' => 'messages',
        'label' => 'Inbox',
        'route' => route('community.messages.index'),
        'title' => 'Direct messages',
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
                class="group flex items-center gap-2 border-b-2 py-3 px-4 sm:px-6 text-sm md:text-lg font-medium transition-all duration-200
                whitespace-nowrap shrink-0 md:flex-none
                {{ $isActive
                    ? 'border-[#ffb901] text-[#ffb901] hover:text-[#ffb901]'
                    : 'border-transparent text-white hover:text-white hover:border-white'
                }}"
            >
                {{ $tab['label'] }}
            </a>

        @endforeach

    </nav>
</div>
