@extends('layouts.app')

@section('content')
    <x-hero 
        title="{{ $pageItem->header_title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Resources'],
            ['label' => 'Research and Publications', 'url' => 'research'],
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <x-resource-nav-tabs active="research" />

    <section class="max-w-8xl md:max-w-7xl mx-auto px-4">
        <form method="GET" action="{{ route('research') }}" class="max-w-xl ml-auto vp-4">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}"
                    placeholder="Search Research & Publications..."
                    class="w-full hp-4 vp-3 pr-16 text-gray-900 bg-gray-200 rounded-full ring-1 ring-[#1f3b67]/50 focus:outline-none focus:ring-2 focus:ring-[#1f3b67] focus:bg-white transition-all"
                >
                <button 
                    type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-[#1f3b67] hover:bg-[#ffb901] rounded-full transition-colors group"
                >
                    <svg class="w-4 h-4 text-white group-hover:text-[#1f3b67] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
            @if($search)
                <div class="vm-3 text-center">
                    <span class="text-gray-600">Search results for: <strong>{{ $search }}</strong></span>
                    <a href="{{ route('research') }}" class="ml-2 text-[#1f3b67] hover:text-[#ffb901] font-medium">Clear search</a>
                </div>
            @endif
        </form>

        @if($items->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($items as $item)
                    @php
                        $publicationFile = $item->file('publication_file');
                        $hasFile = !empty($publicationFile);
                    @endphp
                    
                    <div class="group bg-white shadow-md hover:shadow-xl transition overflow-hidden flex flex-col">
                        <div class="aspect-[210/297] bg-gray-100 overflow-hidden">
                            @if($item->hasImage('publication_cover_image'))
                                <img 
                                    src="{{ $item->image('publication_cover_image', 'potrait') }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition"
                                >
                            @endif
                        </div>

                        <div class="p-2 flex flex-col flex-1">
                            @if(!empty($item->content['date_published']))
                                <p class="text-lg">
                                    {{ \Carbon\Carbon::parse($item->content['date_published'])->format('F Y') }}
                                </p>
                            @endif

                            <h3 class="font-semibold mt-2 mb-4 line-clamp-2">
                                {{ $item->content['title'] ?? 'Untitled' }}
                            </h3>

                            <div class="mt-auto">
                                @if($hasFile)
                                    <a href="{{ $publicationFile }}" target="_blank"
                                       class="block text-center py-2 bg-[#ffb901] hover:bg-[#1f3b67] hover:text-white transition">
                                        Download PDF
                                    </a>
                                @else
                                    <div class="text-center py-2 bg-gray-300">
                                        No File
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $items->links() }}
            </div>
        @else
            <div class="text-center py-20 text-[#1f3b67]">
                <svg class="mx-auto h-12 w-12 text-[#1f3b67]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-xl font-medium text-[#1f3b67]">
                    {{ $search ? 'No publications found' : 'No publications available' }}
                </h3>
            </div>
        @endif

    </section>
@endsection