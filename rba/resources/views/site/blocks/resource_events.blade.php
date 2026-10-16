<div class="container mx-auto px-4 py-12">
    @if(!empty($block->children))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($block->children as $item)
                @if($item->content['event_publish_status'] ?? false)
                    @php
                        $modalId = 'event-modal-' . $item->id;
                        $slideshowImages = [];

                        if (!empty($item->children)) {
                            foreach ($item->children as $slideshowBlock) {
                                if ($slideshowBlock->type !== 'resource_slideshow_repeater') {
                                    continue;
                                }

                                // Force-load the medias relationship — it won't be eager-loaded on nested children
                                $slideshowBlock->load('medias');

                                $images = $slideshowBlock->images('resource_slideshow_image', 'default');

                                foreach ($images as $imageUrl) {
                                    if (!empty($imageUrl)) {
                                        $slideshowImages[] = $imageUrl;
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    <div class="group bg-white shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col">
                        <div class="relative aspect-[3/2] overflow-hidden bg-gray-100">
                            @if($item->hasImage('event_cover_image'))
                                <img 
                                    src="{{ $item->image('event_cover_image', 'default') }}" 
                                    alt="{{ $item->content['event_title'] ?? 'Event cover' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#1f3b67]">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <button 
                                onclick="openEventModal('{{ $modalId }}')"
                                class="absolute bottom-3 right-3 bg-[#ffb901] hover:bg-[#1f3b67] text-[#1f3b67] hover:text-white px-3 py-1.5 rounded shadow-lg transition-colors duration-200 flex items-center gap-2 text-lg font-medium"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Read More
                            </button>
                        </div>

                        <div class="flex-1 py-3 px-4 flex flex-col">
                            @if(!empty($item->content['date_published']))
                                <p class="text-lg text-gray-500 mb-1">
                                    {{ \Carbon\Carbon::parse($item->content['date_published'])->format('F Y') }}
                                </p>
                            @endif

                            <h3 class="text-xl font-semibold text-gray-900 mb-1 line-clamp-2 transition-colors">
                                {{ $item->content['event_title'] ?? 'Untitled Event' }}
                            </h3>

                            @if(!empty($item->content['event_short_description']))
                                <p class="text-lg text-gray-600 mb-1 line-clamp-2">
                                    {{ $item->content['event_short_description'] }}
                                </p>
                            @endif

                            @if(!empty($item->content['event_location']))
                                <p class="text-lg text-gray-500 flex items-center gap-1 mt-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $item->content['event_location'] }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div id="{{ $modalId }}" class="hidden fixed inset-0 z-[45] overflow-y-auto" role="dialog" aria-modal="true" data-current-slide="0" data-total-slides="{{ count($slideshowImages) }}" onclick="closeEventModal('{{ $modalId }}')">
                        <div class="fixed inset-0 backdrop-blur-sm">
                            <div class="absolute inset-0 bg-black/60"></div>
                        </div>
                        <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-10">
                            <div class="relative bg-white shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                                <button 
                                    onclick="closeEventModal('{{ $modalId }}')" 
                                    class="absolute top-6 right-6 p-2 font-semibold bg-[#1F3B67] text-white rounded-full transition-all duration-200 hover:scale-110 active:scale-95 z-10 border-none"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="square" stroke-linejoin="miter" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>

                                <div class="relative p-10">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-6 pr-8">
                                        {{ $item->content['event_title'] ?? 'Event Details' }}
                                    </h3>

                                    @if(!empty($slideshowImages))
                                        <div class="mb-6">
                                            <div class="relative">
                                                <div class="overflow-hidden relative h-96 bg-gray-900">
                                                    @foreach($slideshowImages as $index => $imageUrl)
                                                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                                                            <img 
                                                                src="{{ $imageUrl }}" 
                                                                class="w-full h-full object-cover" 
                                                                alt="Event image {{ $index + 1 }}"
                                                            >
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if(count($slideshowImages) > 1)
                                                    <button 
                                                        type="button" 
                                                        class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" 
                                                        onclick="prevSlide('{{ $modalId }}')"
                                                    >
                                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#ffb901]/70 blue-text hover:bg-[#1f3b67]/70 hover:text-white transition-colors">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <button 
                                                        type="button" 
                                                        class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" 
                                                        onclick="nextSlide('{{ $modalId }}')"
                                                    >
                                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#ffb901]/70 blue-text hover:bg-[#1f3b67]/70 hover:text-white transition-colors">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <div class="carousel-indicators absolute z-30 flex space-x-3 bottom-5 left-0 right-0 justify-center">
                                                        @foreach($slideshowImages as $index => $imageUrl)
                                                            <button 
                                                                type="button" 
                                                                class="w-3 h-3 rounded-full transition-colors {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}"
                                                                onclick="goToSlide('{{ $modalId }}', {{ $index }})"
                                                            ></button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="space-y-4">
                                        @if(!empty($item->content['event_location']))
                                            <div class="flex items-start gap-2">
                                                <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-lg font-semibold text-gray-700">Location</p>
                                                    <p class="text-lg text-gray-600">{{ $item->content['event_location'] }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if(!empty($item->content['date_published']))
                                            <div class="flex items-start gap-2">
                                                <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-lg font-semibold text-gray-700">Date</p>
                                                    <p class="text-lg text-gray-600">{{ \Carbon\Carbon::parse($item->content['date_published'])->format('F d, Y') }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if(!empty($item->content['event_more_info']))
                                            {{-- <div class="prose max-w-none mt-6">
                                                <div class="text-gray-600">
                                                    {!! $item->content['event_more_info'] !!}
                                                </div>
                                            </div> --}}
                                            <x-wysiwyg-div :content="$item->content['event_more_info']"/>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if($block->children->where('content.event_publish_status', 1)->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-xl font-medium text-gray-900">No events available</h3>
                <p class="mt-1 text-lg text-gray-500">Check back later for upcoming events.</p>
            </div>
        @endif
    @else
        <div class="text-center py-16">
            <p class="text-gray-500">No events have been added yet.</p>
        </div>
    @endif
</div>

<script>
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="event-modal-"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeEventModal(modal.id);
            }
        });
    }
});

function openEventModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeEventModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function nextSlide(modalId) {
    const modal = document.getElementById(modalId);
    const slides = modal.querySelectorAll('.carousel-slide');
    const indicators = modal.querySelectorAll('.carousel-indicators button');
    const currentSlide = parseInt(modal.dataset.currentSlide);
    const totalSlides = parseInt(modal.dataset.totalSlides);
    
    const nextSlide = (currentSlide + 1) % totalSlides;
    
    slides[currentSlide].classList.remove('opacity-100');
    slides[currentSlide].classList.add('opacity-0');
    indicators[currentSlide].classList.remove('bg-white');
    indicators[currentSlide].classList.add('bg-white/50');
    
    slides[nextSlide].classList.remove('opacity-0');
    slides[nextSlide].classList.add('opacity-100');
    indicators[nextSlide].classList.remove('bg-white/50');
    indicators[nextSlide].classList.add('bg-white');
    
    modal.dataset.currentSlide = nextSlide;
}

function prevSlide(modalId) {
    const modal = document.getElementById(modalId);
    const slides = modal.querySelectorAll('.carousel-slide');
    const indicators = modal.querySelectorAll('.carousel-indicators button');
    const currentSlide = parseInt(modal.dataset.currentSlide);
    const totalSlides = parseInt(modal.dataset.totalSlides);
    
    const prevSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
    
    slides[currentSlide].classList.remove('opacity-100');
    slides[currentSlide].classList.add('opacity-0');
    indicators[currentSlide].classList.remove('bg-white');
    indicators[currentSlide].classList.add('bg-white/50');
    
    slides[prevSlide].classList.remove('opacity-0');
    slides[prevSlide].classList.add('opacity-100');
    indicators[prevSlide].classList.remove('bg-white/50');
    indicators[prevSlide].classList.add('bg-white');
    
    modal.dataset.currentSlide = prevSlide;
}

function goToSlide(modalId, targetIndex) {
    const modal = document.getElementById(modalId);
    const slides = modal.querySelectorAll('.carousel-slide');
    const indicators = modal.querySelectorAll('.carousel-indicators button');
    const currentSlide = parseInt(modal.dataset.currentSlide);
    
    slides[currentSlide].classList.remove('opacity-100');
    slides[currentSlide].classList.add('opacity-0');
    indicators[currentSlide].classList.remove('bg-white');
    indicators[currentSlide].classList.add('bg-white/50');
    
    slides[targetIndex].classList.remove('opacity-0');
    slides[targetIndex].classList.add('opacity-100');
    indicators[targetIndex].classList.remove('bg-white/50');
    indicators[targetIndex].classList.add('bg-white');
    
    modal.dataset.currentSlide = targetIndex;
}
</script>