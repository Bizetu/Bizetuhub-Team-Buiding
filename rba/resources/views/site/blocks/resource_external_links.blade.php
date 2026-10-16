<div class="container mx-auto px-4 py-12">
    @if(!empty($block->children))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($block->children as $item)
                @if($item->content['publish_status'] ?? false)
                    @php
                        $externalUrl = $item->content['external_url'] ?? '#';
                    @endphp
                    
                    <a 
                        href="{{ $externalUrl }}" 
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group bg-white shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col"
                    >
                        <div class="relative aspect-[7/2] overflow-hidden">
                            @if($item->hasImage('external_link_cover_image'))
                                <img 
                                    src="{{ $item->image('external_link_cover_image', 'default') }}" 
                                    alt="{{ $item->content['title'] ?? 'External link cover' }}"
                                    class="p-3 mx-auto max-w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#1f3b67]">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="hidden hover:block absolute top-3 right-3 bg-white/90 rounded-full p-2 shadow-lg">
                                <svg class="w-4 h-4 text-[#1f3b67]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 p-2 flex flex-col bg-gray-100">
                            <h3 class="text-xl font-semibold text-center mb-4 line-clamp-2 group-hover:text-[#1f3b67] transition-colors">
                                {{ $item->content['title'] ?? '-' }}
                            </h3>

                            <div class="mt-auto mx-auto p-1 flex items-center text-lg text-[#1f3b67] font-medium bg-[#ffb901] rounded-full">
                                <x-web-icon />
                                <span class="mx-1">Visit Website</span>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>

        @if($block->children->where('content.publish_status', 1)->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <h3 class="mt-2 text-xl font-medium text-gray-900">No external links available</h3>
                <p class="mt-1 text-lg text-gray-500">Check back later for new resources.</p>
            </div>
        @endif
    @else
        <div class="text-center py-16">
            <p class="text-gray-500">No external links have been added yet.</p>
        </div>
    @endif
</div>