<div class="container mx-auto px-4 py-12">
    @if(!empty($block->children))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($block->children as $item)
                @if($item->content['publish_status'] ?? false)
                    @php
                        $videoType = $item->content['video_type'] ?? 'youtube';
                        $mp4File = null;
                        try {
                            $mp4File = $item->file('resource_mp4_video');
                        } catch (\Exception $e) {
                            // File not exist
                        }
                        
                        $videoUrl = $videoType === 'mp4' ? $mp4File : ($item->content['youtube_video'] ?? '');
                        $hasVideo = !empty($videoUrl);
                        $modalId = 'video-modal-' . $item->id;
                        
                        $youtubeId = '';
                        if ($videoType === 'youtube' && !empty($videoUrl)) {
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                                $youtubeId = $matches[1];
                            }
                        }
                    @endphp
                    
                    <div class="group bg-white shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col">
                        <div class="relative aspect-[3/2] sm:aspect-[9/10] overflow-hidden bg-gray-100">
                            @if($item->hasImage('resource_video_cover_image'))
                                <img 
                                    src="{{ $item->image('resource_video_cover_image', 'default') }}" 
                                    alt="{{ $item->content['title'] ?? 'Video cover' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#1f3b67]">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <button 
                                onclick="openVideoModal('{{ $modalId }}')"
                                class="absolute bottom-3 right-3 bg-[#ffb901] hover:bg-[#1f3b67] text-[#1f3b67] hover:text-white px-3 py-1.5 rounded-full shadow-lg transition-colors duration-200 flex items-center gap-2 text-lg font-medium"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                                Watch Video
                            </button>
                        </div>

                        <div class="flex-1 p-2 flex flex-col">
                            @if(!empty($item->content['date_published']))
                                <p class="text-lg text-gray-500 mb-2">
                                    {{ \Carbon\Carbon::parse($item->content['date_published'])->format('F Y') }}
                                </p>
                            @endif

                            <h3 class="text-xl font-semibold text-gray-900 mb-4 line-clamp-2 transition-colors">
                                {{ $item->content['title'] ?? 'Untitled Video' }}
                            </h3>
                        </div>
                    </div>

                    <div id="{{ $modalId }}" class="hidden fixed inset-0 z-[45] overflow-y-auto" role="dialog" aria-modal="true" onclick="closeVideoModal('{{ $modalId }}')">
                        <div class="fixed inset-0 backdrop-blur-sm">
                            <div class="absolute inset-0 bg-black/60"></div>
                        </div>

                        <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-6">
                            <div class="relative bg-white shadow-2xl w-full max-w-4xl" onclick="event.stopPropagation()">
                                <button 
                                    onclick="closeVideoModal('{{ $modalId }}')" 
                                    class="absolute top-6 right-6 p-2 font-semibold bg-[#1F3B67] text-white rounded-full transition-all duration-200 hover:scale-110 active:scale-95 z-10 border-none"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="square" stroke-linejoin="miter" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>

                                <div class="relative p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4 pr-8">
                                        {{ $item->content['title'] ?? 'Video' }}
                                    </h3>

                                    <div class="aspect-video bg-black overflow-hidden">
                                        @if($videoType === 'youtube' && !empty($youtubeId))
                                            <iframe 
                                                class="w-full h-full"
                                                src="https://www.youtube.com/embed/{{ $youtubeId }}?loop=1&playlist={{ $youtubeId }}&controls=0&showinfo=0&modestbranding=1&rel=0&enablejsapi=1"
                                                frameborder="0" 
                                                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen
                                            ></iframe>
                                        @elseif($videoType === 'youtube' && empty($youtubeId))
                                            <div class="w-full h-full flex items-center justify-center text-white">
                                                Invalid YouTube URL
                                            </div>
                                        @elseif($videoType === 'mp4' && $hasVideo)
                                            <video 
                                                class="w-full h-full"
                                                controls
                                                preload="metadata"
                                            >
                                                <source src="{{ $videoUrl }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white">
                                                Video not available
                                            </div>
                                        @endif
                                    </div>

                                    @if(!empty($item->content['date_published']))
                                        <p class="text-lg text-gray-500 mt-4">
                                            Published: {{ \Carbon\Carbon::parse($item->content['date_published'])->format('F d, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if($block->children->where('content.publish_status', 1)->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-xl font-medium text-gray-900">No videos available</h3>
                <p class="mt-1 text-lg text-gray-500">Check back later for new videos.</p>
            </div>
        @endif
    @else
        <div class="text-center py-16">
            <p class="text-gray-500">No videos have been added yet.</p>
        </div>
    @endif
</div>

<script>
function openVideoModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeVideoModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        const videos = modal.querySelectorAll('video');
        videos.forEach(video => {
            video.pause();
            video.currentTime = 0;
        });
        
        const iframes = modal.querySelectorAll('iframe');
        iframes.forEach(iframe => {
            const src = iframe.src;
            iframe.src = src;
        });
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="video-modal-"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeVideoModal(modal.id);
            }
        });
    }
});
</script>