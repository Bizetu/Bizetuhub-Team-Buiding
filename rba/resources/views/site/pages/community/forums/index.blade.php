@extends('layouts.app')

@section('content')

    <x-hero 
        title="Discussion Forums"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Forums', 'url' => route('forums')],
        ]"
        :backgroundImage="asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <x-community-nav-tabs active="forums" />

    <div class="my-2 px-4 min-h-[379px]">
        <div class="max-w-7xl mx-auto">
            
            {{-- Create Forum Button (HR/Admin only) --}}
            @if($canCreateForum)
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('community.forums.create') }}" class="inline-flex items-center hp-4 vp-2 blue-bg text-white rounded font-medium hover:opacity-90 transition-opacity">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Forum
                    </a>
                </div>
            @endif

            {{-- Forums List --}}
            @if($forums->count() > 0)
                <div class="grid grid-cols-1 gap-6">
                    @foreach($forums as $forum)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4 sm:p-6">
                                {{-- Mobile: Stack vertically, Desktop: Side by side --}}
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        {{-- Title and badges --}}
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <h3 class="text-xl sm:text-xl font-bold blue-text">{{ $forum->title }}</h3>
                                            
                                            @if($forum->user_is_creator)
                                                <span class="px-2 py-1 yellow-bg text-gray-100 text-base font-semibold rounded whitespace-nowrap">
                                                    Creator
                                                </span>
                                            @elseif($forum->user_is_member)
                                                <span class="px-2 py-1 blue-bg text-gray-100 text-base font-semibold rounded whitespace-nowrap">
                                                    Member
                                                </span>
                                            @elseif($forum->user_status === 0)
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-base font-semibold rounded whitespace-nowrap">
                                                    Pending
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Description --}}
                                        <p class="mb-4 text-lg sm:text-lg">{{ $forum->description }}</p>

                                        {{-- Stats - Stack on mobile, inline on desktop --}}
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 text-base sm:text-lg text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="truncate">Created by {{ $forum->creator->name_value }}</span>
                                            </div>
                                            @if($forum->user_is_creator && ($canCreateForum))
                                                <a href="{{ route('community.forums.members', $forum->id) }}" class="flex items-center hover:underline blue-text font-medium">
                                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <span>{{ $forum->approved_members_count }} {{ Str::plural('member', $forum->approved_members_count) }}</span>
                                                </a>
                                            @else
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    <span>{{ $forum->approved_members_count }} {{ Str::plural('member', $forum->approved_members_count) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $forum->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- View button - Full width on mobile, auto on desktop --}}
                                    <div class="w-full sm:w-auto sm:ml-4 flex-shrink-0">
                                        <a href="{{ route('community.forums.show', $forum->id) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 blue-bg text-white rounded text-lg font-medium hover:opacity-90 transition-opacity">
                                            View Forum
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $forums->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No forums yet</h3>
                    <p class="mt-1 text-lg text-gray-500">
                        @if($canCreateForum)
                            Be the first to create a discussion forum.
                        @else
                            Check back later for new discussion forums.
                        @endif
                    </p>
                </div>
            @endif

        </div>
    </div>

@endsection