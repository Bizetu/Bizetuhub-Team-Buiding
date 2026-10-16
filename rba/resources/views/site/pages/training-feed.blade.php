@extends('layouts.app')

@section('title', 'Training Feed - Rwanda Bankers Association')

@section('content')

    <x-hero
        title="Training Feed"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Training Feed', 'url' => route('profile.trainingFeed')]
        ]"
        height="h-80"
    />

    <x-profile-nav-tabs active="trainingFeed" />

    <div class="max-w-8xl md:max-w-7xl mx-auto px-2 md:px-0 py-8">
        
        {{-- Header Text --}}
        <div class="mb-8">
            <p class="text-center text-gray-600 text-lg lg:text-lg max-w-4xl mx-auto">
                Stay updated and inspired by the training milestones achieved by the colleagues you follow. 
                Congratulate them and give them Kudos for their achievements!
            </p>
        </div>

        {{-- Filters --}}
        {{-- <div class="mb-6 bg-white shadow-sm rounded-lg p-4">
            <form method="GET" action="{{ route('profile.trainingFeed') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-3">
                    <select name="year" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            onchange="this.form.submit()">
                        <option value="">All Years</option>
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="md:col-span-3">
                    <select name="month" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            onchange="this.form.submit()">
                        <option value="">All Months</option>
                        @for($month = 1; $month <= 12; $month++)
                            <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="md:col-span-3">
                    <select name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                
                <div class="md:col-span-3">
                    @if(request()->hasAny(['year', 'month', 'status']))
                        <a href="{{ route('profile.trainingFeed') }}" 
                           class="block w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center rounded-lg font-medium transition-colors">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div> --}}

        {{-- Feed Content --}}
        @if (!count($licenses))
            <div class="bg-white shadow-sm rounded-lg p-12">
                <div class="text-center">
                    <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Training Activity Yet</h3>
                    <p class="text-gray-600 mb-6">
                        Follow colleagues and engage each other on your training progress.
                    </p>
                    <a href="{{ route('community.index') }}" 
                       class="inline-block px-6 py-3 bg-[#1f3b67] hover:bg-[#233e69] text-white font-medium rounded-lg transition-colors">
                        Find Colleagues to Follow
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($licenses as $license)
                    @if(!empty($license->course))
                        <x-training-feed-card :license="$license"/>
                    @endif
                @endforeach
            </div>
            <div class="mt-4">
                {!! $licenses->links() !!}
            </div>
        @endif

    </div>

@endsection