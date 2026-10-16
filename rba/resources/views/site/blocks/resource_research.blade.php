<div class="container mx-auto hp-4 vp-12">
    @if(!empty($block->children))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($block->children as $item)
                @if($item->content['publish_status'] ?? false)
                    @php
                        $publicationFile = $item->file('publication_file');
                        $hasFile = !empty($publicationFile);
                    @endphp
                    
                    <div class="group bg-white shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col">
                        <div class="relative aspect-[210/297] overflow-hidden bg-gray-100">
                            @if($item->hasImage('publication_cover_image'))
                                <img 
                                    src="{{ $item->image('publication_cover_image', 'potrait') }}" 
                                    alt="{{ $item->content['title'] ?? 'Publication cover' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#1f3b67]">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 p-2 flex flex-col">
                            @if(!empty($item->content['date_published']))
                                <p class="text-lg text-gray-500 mb-2">
                                    {{ \Carbon\Carbon::parse($item->content['date_published'])->format('F Y') }}
                                </p>
                            @endif

                            <h3 class="text-xl font-semibold text-gray-900 mb-4 line-clamp-2 transition-colors">
                                {{ $item->content['title'] ?? 'Untitled Publication' }}
                            </h3>

                            <div class="mt-auto">
                                @if($hasFile)
                                    <a 
                                        href="{{ $publicationFile }}" 
                                        target="_blank"
                                        class="inline-flex items-center justify-center w-full hp-4 py-1 bg-[#ffb901] hover:bg-[#1f3b67] text-[#1f3b67] hover:text-white font-medium transition-colors duration-200 shadow-sm hover:shadow-md"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Download PDF
                                    </a>
                                @else
                                    <button disabled class="inline-flex items-center justify-center w-full hp-4 py-1 bg-gray-300 text-gray-500 font-medium cursor-not-allowed">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        No File Available
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if($block->children->where('content.publish_status', 1)->isEmpty())
            <div class="text-center vp-16">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-xl font-medium text-gray-900">No publications available</h3>
                <p class="mt-1 text-lg text-gray-500">Check back later for new research publications.</p>
            </div>
        @endif
    @else
        <div class="text-center vp-16">
            <p class="text-gray-500">No research publications have been added yet.</p>
        </div>
    @endif
</div>