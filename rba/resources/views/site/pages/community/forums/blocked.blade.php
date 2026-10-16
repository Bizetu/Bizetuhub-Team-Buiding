@extends('layouts.app')

@section('content')

    <x-hero 
        title="{{ $forum->title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Forums', 'url' => route('forums')],
            ['label' => $forum->title, 'url' => route('community.forums.show', $forum->id)],
        ]"
        :backgroundImage="asset('images/defaults/banner.webp')"
        height="h-80"
    />

    <div class="my-10 px-4">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 sm:p-8">
                {{-- Forum Info --}}
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold blue-text mb-3">{{ $forum->title }}</h2>
                    <p class="text-lg sm:text-xl subtle-gray-text mb-6">{{ $forum->description }}</p>

                    <div class="flex items-center justify-center gap-6 text-lg subtle-gray-text mb-8">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Created by {{ $forum->creator->name_value }}
                        </div>
                    </div>
                </div>

                {{-- Blocked Message --}}
                <div class="max-w-md mx-auto">
                    <div class="text-center p-6 bg-red-50 border border-red-200 rounded-lg">
                        <svg class="w-16 h-16 mx-auto mb-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-red-800 mb-2">Access Restricted</h3>
                        <p class="text-lg text-red-700 mb-4">You have been blocked from accessing this forum by the moderator.</p>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('forums') }}" class="inline-flex items-center text-lg blue-text hover:underline">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Forums
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection