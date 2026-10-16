@if ($block->children && count($block->children) > 0)
    <div class="absolute inset-0">
        @foreach ($block->children as $key => $item)
            @php
                $videoUrl = $item->input('video_url');
                $bunnyUrl = $item->input('bunny_net_video_url');
                $videoId = null;

                // Parse YouTube URL to get video ID
                if ($videoUrl) {
                    // Match various YouTube URL formats
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                    }
                }

                $hasYtVid = !empty($videoId);
                $hasBunnyVid = !empty($bunnyUrl);
            @endphp

            <!-- Slide {{ $key + 1 }}: {{ $hasYtVid ? 'Video' : 'Image' }} -->
            <div class="slide {{ $key == 0 ? 'active' : '' }} absolute inset-0 opacity-0 transition-opacity duration-1000" data-slide="{{ $key }}">
                @if ($hasYtVid)
                    <iframe
                        class="absolute top-1/2 left-1/2 w-[177.77vh] h-[56vh] md:h-[83vh] lg:h-[84vh] xl:h-[87vh] min-h-[56.25vw] min-w-[100vw] -translate-x-1/2 -translate-y-1/2 pointer-events-none"
                        src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=1&loop=1&playlist={{ $videoId }}&controls=0&showinfo=0&modestbranding=1&rel=0&enablejsapi=1"
                        frameborder="0"
                        allow="autoplay;"
                        id="video-{{ $key }}"
                        title="Slide {{ $key + 1 }} video"
                    ></iframe>
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                @elseif ($hasBunnyVid)
                    <iframe
                        src="{{ $bunnyUrl }}"
                        loading="lazy"
                        class="absolute top-1/2 left-1/2 w-[177.77vh] h-[100vh] min-w-[100vw] min-h-[56.25vw] -translate-x-1/2 -translate-y-1/2 pointer-events-none"
                        allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;"
                        id="bunny-{{ $key }}"
                        title="Slide {{ $key + 1 }} video"
                        allowfullscreen="true"
                    ></iframe>
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                @else
                    @if ($item->hasImage('slide_image'))
                        <img
                            src="{{ $item->image('slide_image', 'default') }}"
                            alt="{{ $item->input('title') ?: 'Slide image' }}"
                            class="absolute top-1/2 left-1/2 w-full h-full object-cover -translate-x-1/2 -translate-y-1/2"
                        />
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                @endif

                <div class="relative h-80 md:h-full flex items-center justify-start text-left px-2 md:px-16 lg:px-24 z-10">
                    <div class="max-w-full sm:max-w-6xl my-20 md:my-16 mx-2 md:mx-8">
                        @php
                            $titleParts = split_title($item->input('title'), 12);
                        @endphp

                        @if($item->input('title'))
                            <h1 class="text-2xl md:text-3xl lg:text-5xl xl:text-6xl font-extrabold text-white mt-20 mb-6 tracking-tight break-words w-80 md:w-[70vw] lg:w-[60vw] leading-[1.4] md:leading-[1.2]">
                                <span class="text-[#FFB901]">{!! $titleParts['first'] !!}</span>
                                @if($titleParts['middle'])<span>{!! ' ' . $titleParts['middle'] . ' ' !!}</span>@endif
                                @if($titleParts['last2'])<span class="text-[#FFB901]">{!! $titleParts['last2'] !!}</span>@endif
                            </h1>
                        @endif

                        <div class="flex flex-row gap-2 sm:gap-3 justify-start items-start">
                            @guest
                                <a href="{{ route('login.choice') }}" class="inline-block transition duration-200 font-bold text-xl yellow-button-rounded-full">
                                    Sign In
                                </a>
                            @endguest

                            @if ($item->input('url'))
                                <a href="{{ $item->input('url') }}" class="inline-block transition duration-200 font-bold text-lg md:text-xl blue-button-rounded-full">
                                    {{ $item->input('link_text') ?: 'Learn More' }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Dots Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 hidden sm:flex gap-2" id="dotsContainer">
        @foreach ($block->children as $key => $item)
            <button class="dot w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $key == 0 ? 'bg-white' : 'bg-gray-400 hover:bg-gray-300' }}" data-slide="{{ $key }}" aria-label="Go to slide {{ $key + 1 }}"></button>
        @endforeach
    </div>

    <!-- Navigation Controls -->
    <div class="absolute bottom-8 right-8 z-20 flex items-center gap-3">
        <!-- mute/un-mutee btn -->
        <button id="muteBtn" class="hidden yellow-bg blue-text hover:bg-[#1f3b67] hover:text-white bg-opacity-20 hover:bg-opacity-30 p-2 md:p-3 rounded-full backdrop-blur-sm transition duration-200" aria-label="Mute/Unmute" title="Mute/Unmute">
            <svg id="unmuteIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
            </svg>
            <svg id="muteIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
            </svg>
        </button>
        <!-- Play/Pause Button -->
        <button id="playPauseBtn" class="yellow-bg blue-text hover:bg-[#1f3b67] hover:text-white bg-opacity-20 hover:bg-opacity-30 p-2 md:p-3 rounded-full backdrop-blur-sm transition duration-200" aria-label="Play/Pause slideshow" title="Play/Pause slideshow">
            <svg id="pauseIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
            </svg>
            <svg id="playIcon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
            </svg>
        </button>

        <!-- Previous Button -->
        <button id="prevBtn" class="yellow-bg blue-text hover:bg-[#1f3b67] hover:text-white bg-opacity-20 hover:bg-opacity-30 p-2 md:p-3 rounded-full backdrop-blur-sm transition duration-200" aria-label="Previous slide" title="Previous slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Next Button -->
        <button id="nextBtn" class="yellow-bg blue-text hover:bg-[#1f3b67] hover:text-white bg-opacity-20 hover:bg-opacity-30 p-2 md:p-3 rounded-full backdrop-blur-sm transition duration-200" aria-label="Next slide" title="Next slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    @push('styles')
    <style>
        .slide {
            opacity: 0;
            z-index: 0;
        }
        .slide.active {
            opacity: 1;
            z-index: 1;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentSlide = 0;
            let isPlaying = true;
            let autoPlayInterval;
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');
            const totalSlides = slides.length;

            // YouTube API for video control
            let youtubeAPIReady = false;
            let players = {};

            // Load YouTube IFrame API if there are video slides
            const hasYouTubeVideos = document.querySelectorAll('iframe[src*="youtube.com"]').length > 0;
            if (hasYouTubeVideos) {
                const tag = document.createElement('script');
                tag.src = 'https://www.youtube.com/iframe_api';
                const firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                window.onYouTubeIframeAPIReady = function() {
                    youtubeAPIReady = true;
                    document.querySelectorAll('iframe[id^="video-"]').forEach((iframe) => {
                        if (iframe.src.includes('youtube.com')) {
                            const slideIndex = parseInt(iframe.id.split('-')[1]);
                            players[slideIndex] = new YT.Player(iframe.id, {
                                events: {
                                    'onReady': function(event) {
                                        if (slideIndex === 0 && isPlaying) {
                                            event.target.playVideo();
                                        }
                                    }
                                }
                            });
                        }
                    });
                };
            }

            function goToSlide(index) {
                if (players[currentSlide] && youtubeAPIReady) {
                    try {
                        players[currentSlide].pauseVideo();
                    } catch (e) {
                        console.log('Error pausing video:', e);
                    }
                }

                // Pause bunny.net video when leaving slide
                const prevSlideElement = slides[currentSlide];
                const prevIframe = prevSlideElement.querySelector('iframe[src*="mediadelivery.net"]');
                if (prevIframe) {
                    const prevUrl = new URL(prevIframe.src);
                    prevUrl.searchParams.set('autoplay', 'false');
                    prevIframe.src = prevUrl.toString();
                }

                slides[currentSlide].classList.remove('active');

                dots[currentSlide].classList.remove('bg-white');
                dots[currentSlide].classList.add('bg-white/40');

                currentSlide = index;

                slides[currentSlide].classList.add('active');

                dots[currentSlide].classList.remove('bg-white/40');
                dots[currentSlide].classList.add('bg-white');

                // Play YouTube video if slideshow is playing
                if (players[currentSlide] && youtubeAPIReady && isPlaying) {
                    try {
                        players[currentSlide].playVideo();
                    } catch (e) {
                        console.log('Error playing video:', e);
                    }
                }

                // Restart bunny.net video if slideshow is playing
                const currentSlideElement = slides[currentSlide];
                const currentIframe = currentSlideElement.querySelector('iframe[src*="mediadelivery.net"]');
                if (currentIframe && isPlaying) {
                    const currentUrl = new URL(currentIframe.src);
                    currentUrl.searchParams.set('autoplay', 'true');
                    currentIframe.src = currentUrl.toString();
                }

                updateMuteButton();
            }

            function nextSlide() {
                goToSlide((currentSlide + 1) % totalSlides);
            }

            function prevSlide() {
                goToSlide((currentSlide - 1 + totalSlides) % totalSlides);
            }

            function togglePlayPause() {
                isPlaying = !isPlaying;
                const pauseIcon = document.getElementById('pauseIcon');
                const playIcon = document.getElementById('playIcon');

                if (isPlaying) {
                    pauseIcon.classList.remove('hidden');
                    playIcon.classList.add('hidden');
                    startAutoPlay();

                    // Resume YouTube video
                    if (players[currentSlide] && youtubeAPIReady) {
                        try {
                            players[currentSlide].playVideo();
                        } catch (e) {}
                    }

                    // Resume Bunny.net video
                    const currentSlideElement = slides[currentSlide];
                    const iframe = currentSlideElement.querySelector('iframe[src*="mediadelivery.net"]');
                    if (iframe) {
                        const url = new URL(iframe.src);
                        url.searchParams.set('autoplay', 'true');
                        iframe.src = url.toString();
                    }
                } else {
                    pauseIcon.classList.add('hidden');
                    playIcon.classList.remove('hidden');
                    stopAutoPlay();

                    // Pause YouTube video
                    if (players[currentSlide] && youtubeAPIReady) {
                        try {
                            players[currentSlide].pauseVideo();
                        } catch (e) {}
                    }

                    // Pause Bunny.net video
                    const currentSlideElement = slides[currentSlide];
                    const iframe = currentSlideElement.querySelector('iframe[src*="mediadelivery.net"]');
                    if (iframe) {
                        const url = new URL(iframe.src);
                        url.searchParams.set('autoplay', 'false');
                        iframe.src = url.toString();
                    }
                }
            }

            function updateMuteButton() {
                const currentSlideElement = slides[currentSlide];
                const muteBtn = document.getElementById('muteBtn');
                const iframe = currentSlideElement.querySelector('iframe');

                if (iframe) {
                    muteBtn.classList.remove('hidden');
                } else {
                    muteBtn.classList.add('hidden');
                }
            }

            function toggleMute() {
                const currentSlideElement = slides[currentSlide];
                const iframe = currentSlideElement.querySelector('iframe');
                const muteIcon = document.getElementById('muteIcon');
                const unmuteIcon = document.getElementById('unmuteIcon');

                if (!iframe) return;

                const isMuted = muteIcon.classList.contains('hidden');

                if (iframe.src.includes('youtube.com')) {
                    // YouTube video
                    if (players[currentSlide] && youtubeAPIReady) {
                        if (isMuted) {
                            players[currentSlide].mute();
                            muteIcon.classList.remove('hidden');
                            unmuteIcon.classList.add('hidden');
                        } else {
                            players[currentSlide].unMute();
                            muteIcon.classList.add('hidden');
                            unmuteIcon.classList.remove('hidden');
                        }
                    }
                } else if (iframe.src.includes('mediadelivery.net')) {
                    // Bunny.net video - update URL
                    const url = new URL(iframe.src);
                    if (isMuted) {
                        url.searchParams.set('muted', 'true');
                        muteIcon.classList.remove('hidden');
                        unmuteIcon.classList.add('hidden');
                    } else {
                        url.searchParams.set('muted', 'false');
                        muteIcon.classList.add('hidden');
                        unmuteIcon.classList.remove('hidden');
                    }
                    iframe.src = url.toString();
                }
            }

            function startAutoPlay() {
                stopAutoPlay();
                autoPlayInterval = setInterval(nextSlide, 10000);
            }

            function stopAutoPlay() {
                if (autoPlayInterval) {
                    clearInterval(autoPlayInterval);
                }
            }

            // Event Listeners
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const muteBtn = document.getElementById('muteBtn');
            if (muteBtn) {
                muteBtn.addEventListener('click', toggleMute);
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    if (isPlaying) startAutoPlay();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    if (isPlaying) startAutoPlay();
                });
            }

            if (playPauseBtn) {
                playPauseBtn.addEventListener('click', togglePlayPause);
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    goToSlide(index);
                    if (isPlaying) startAutoPlay();
                });
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                    if (isPlaying) startAutoPlay();
                }
                if (e.key === 'ArrowRight') {
                    nextSlide();
                    if (isPlaying) startAutoPlay();
                }
                if (e.key === ' ') {
                    e.preventDefault();
                    togglePlayPause();
                }
            });

            // Start autoplay
            if (totalSlides > 1) {
                startAutoPlay();
            }
            updateMuteButton();
        });
    </script>
    @endpush
@endif
