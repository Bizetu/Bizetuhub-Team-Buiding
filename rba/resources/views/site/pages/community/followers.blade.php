@extends('layouts.app')

@section('content')

    <x-hero 
        title="Followers"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Community', 'url' => route('community.index')],
            ['label' => 'Followers', 'url' => route('community.followers')],
        ]"
        :backgroundImage="$pageItem?->image('hero_image') ?? asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <x-community-nav-tabs active="followers" />

    <div class="my-2 px-4 min-h-[35vh] lg:min-h-[379px]">
        <div class="max-w-7xl mx-auto">
            
            {{-- Followers List --}}
            @if($followers->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($followers as $follower)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <img 
                                            src="{{ $follower->profile_pic_url }}" 
                                            alt="{{ $follower->name_value }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-gray-100"
                                        >
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $follower->name_value }}</h3>
                                            @if($follower->company)
                                                <p class="text-lg text-gray-600">{{ $follower->company->title }}</p>
                                            @endif
                                            @if($follower->jobRole)
                                                <p class="text-base text-gray-500">{{ $follower->jobRole->title }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a 
                                        href="{{ route('community.messages.show', $follower->id) }}"
                                        class="block w-full px-4 py-2 bg-black text-white rounded text-lg font-medium text-center hover:bg-gray-800 transition-colors"
                                    >
                                        Message
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $followers->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No followers yet</h3>
                    <p class="mt-1 text-lg text-gray-500">When people follow you, they'll appear here.</p>
                </div>
            @endif

        </div>
    </div>

@endsection