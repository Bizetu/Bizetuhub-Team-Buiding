@extends('layouts.app')

@section('content')

    <x-hero 
        title="Messages"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'My Community', 'url' => route('community.index')],
            ['label' => 'Messages', 'url' => route('community.messages.index')],
        ]"
        :backgroundImage="$pageItem?->image('hero_image') ?? asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <x-community-nav-tabs active="messages" />

    <div class="my-2 px-4 min-h-[35vh] lg:min-h-[379px]">
        <div class="max-w-7xl mx-auto">
            
            {{-- Conversations List --}}
            @if($conversationUsers->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    @foreach($conversationUsers as $conversationUser)
                        <a 
                            href="{{ route('community.messages.show', $conversationUser->id) }}"
                            class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors border-b border-gray-200 last:border-b-0"
                        >
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <img 
                                        src="{{ $conversationUser->profile_pic_url }}" 
                                        alt="{{ $conversationUser->name_value }}"
                                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-100"
                                    >
                                    @if($conversationUser->unread_count > 0)
                                        <span class="absolute -top-1 -right-1 bg-black text-white text-base rounded-full h-5 w-5 flex items-center justify-center">
                                            {{ $conversationUser->unread_count }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $conversationUser->name_value }}</h3>
                                    @if($conversationUser->company)
                                        <p class="text-lg text-gray-600">{{ $conversationUser->company->title }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-base text-gray-500">
                                    {{ \Carbon\Carbon::parse($conversationUser->last_message_at)->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No messages yet</h3>
                    <p class="mt-1 text-lg text-gray-500">Start a conversation with someone you follow.</p>
                    <div class="mt-6">
                        <a href="{{ route('community.following') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-[#1f3b67]">
                            View Following
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection