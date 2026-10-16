<div class="mx-auto px-4 py-6">
    @if (!empty($block->children))

        {{-- Slider wrapper with arrows --}}
        <div class="relative mx-auto bg-gray-200 rounded-md">

            {{-- Previous Arrow --}}
            <button id="prev-arrow"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 z-10 bg-white border border-gray-200 rounded-full w-7 h-7 flex items-center justify-center shadow-sm hover:bg-[#FFB901] hover:border-[#FFB901] transition-colors duration-200 disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- Slider on mobile, grid on desktop --}}
            <div class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide lg:grid lg:grid-cols-4 lg:overflow-visible lg:justify-items-center mb-4"
                id="esg-pillars">
                @php $index = 0; @endphp
                @foreach ($block->children as $item)
                    @if ($item->content['publish_status'] ?? false)
                        <div class="pillar-card flex-shrink-0 snap-start flex flex-col rounded-lg cursor-pointer transition-colors duration-300 w-full"
                            data-index="{{ $index }}" data-desc="{!! e($item->content['pillar_more_info']) !!}"
                            data-title="{{ $item->content['title'] ?? '' }}"
                            data-image="{{ $item->hasImage('pillar_image_icon') ? $item->image('pillar_image_icon', 'default') : '' }}">
                            <div class="flex flex-row justify-center gap-2 vp-2 rounded-md pillar-highlight w-full">
                                @if ($item->hasImage('pillar_image_icon'))
                                    <img src="{{ $item->image('pillar_image_icon', 'default') }}"
                                        alt="{{ $item->content['title'] ?? 'ESG cover' }}"
                                        class="w-6 h-6 object-cover flex-shrink-0">
                                @else
                                    <div class="h-6 w-6 flex items-center justify-center bg-[#1f3b67] flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <span
                                    class="pillar-title font-medium text-gray-800 text-lg">{!! $item->content['title'] !!}</span>
                            </div>
                        </div>
                        @php $index++; @endphp
                    @endif
                @endforeach
            </div>

            {{-- Next Arrow --}}
            <button id="next-arrow"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 z-10 bg-white border border-gray-200 rounded-full w-7 h-7 flex items-center justify-center shadow-sm hover:bg-[#FFB901] hover:border-[#FFB901] transition-colors duration-200 disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        {{-- Full-width description panel --}}
        <div class="w-full mt-2" id="pillar-desc-panel">
            <div class="flex items-start gap-4">

            </div>
            <div class="flex items-start gap-2">
                <img id="pillar-desc-image" src="" alt=""
                    class="w-8 h-8 object-cover flex-shrink-0 hidden">
                <div id="pillar-desc-icon"
                    class="h-8 w-8 flex items-center justify-center bg-[#1f3b67] flex-shrink-0 hidden">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <h4 id="pillar-desc-title" class="font-semibold text-gray-800 text-lg mt-2"></h4>
            </div>
            <div class="flex flex-col gap-1 mt-2">
                <div id="pillar-desc-text" class="text-lg text-gray-600 leading-relaxed [&>p]:mb-3"></div>
            </div>
        </div>

        @if ($block->children->where('content.publish_status', 1)->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                </svg>
                <h3 class="mt-2 text-xl font-medium text-gray-900">No guidelines available</h3>
                <p class="mt-1 text-lg text-gray-500">Check back later for guidelines.</p>
            </div>
        @endif
    @else
        <div class="text-center py-16">
            <p class="text-gray-500">No guidelines have been added yet.</p>
        </div>
    @endif
</div>


<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('#esg-pillars .pillar-card');
        const descText = document.getElementById('pillar-desc-text');
        const slider = document.getElementById('esg-pillars');
        const prevBtn = document.getElementById('prev-arrow');
        const nextBtn = document.getElementById('next-arrow');

        let activeIndex = 0;
        let autoScrollTimer = null;

        function decodeHtml(html) {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }

        function updateArrows() {
            if (window.innerWidth >= 1024) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                return;
            }
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
            prevBtn.disabled = activeIndex === 0;
            nextBtn.disabled = activeIndex === cards.length - 1;
        }

        let userHasInteracted = false;

        function setActive(index, fromUser = false) {
            activeIndex = index;

            cards.forEach((card, i) => {
                const highlight = card.querySelector('.pillar-highlight');
                if (i === index) {
                    highlight.classList.add('bg-[#FFB901]');
                } else {
                    highlight.classList.remove('bg-[#FFB901]');
                }
            });

            const card = cards[index];
            const image = document.getElementById('pillar-desc-image');
            const icon = document.getElementById('pillar-desc-icon');
            const title = document.getElementById('pillar-desc-title');

            if (card?.dataset.image) {
                image.src = card.dataset.image;
                image.alt = card.dataset.title ?? '';
                image.classList.remove('hidden');
                icon.classList.add('hidden');
            } else {
                image.classList.add('hidden');
                icon.classList.remove('hidden');
            }

            title.textContent = card?.dataset.title ?? '';
            descText.innerHTML = decodeHtml(card?.dataset.desc ?? '');

            card?.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
            updateArrows();

            if (fromUser) {
                userHasInteracted = true;
                stopAutoScroll();
            }
        }

        function startAutoScroll(delay = 5000) {
            if (userHasInteracted) return; // never autoscroll again after user interaction
            autoScrollTimer = setTimeout(function tick() {
                if (userHasInteracted) return;
                const nextIndex = (activeIndex + 1) % cards.length;
                setActive(nextIndex);
                autoScrollTimer = setTimeout(tick, 3000);
            }, delay);
        }

        function stopAutoScroll() {
            clearTimeout(autoScrollTimer);
            autoScrollTimer = null;
        }

        prevBtn.addEventListener('click', () => {
            if (activeIndex > 0) setActive(activeIndex - 1, true);
        });

        nextBtn.addEventListener('click', () => {
            if (activeIndex < cards.length - 1) setActive(activeIndex + 1, true);
        });

        window.addEventListener('resize', updateArrows);

        if (cards.length > 0) {
            setActive(0);
            startAutoScroll();
        }

        cards.forEach((card, i) => {
            card.addEventListener('click', () => setActive(i, true));
        });
        // Pause on hover — only resume if user hasn't interacted
        slider.addEventListener('mouseenter', stopAutoScroll);
        slider.addEventListener('mouseleave', () => {
            if (!userHasInteracted) startAutoScroll();
        });

        // Pause on touch — only resume if user hasn't interacted
        slider.addEventListener('touchstart', stopAutoScroll, {
            passive: true
        });
        slider.addEventListener('touchend', () => {
            if (!userHasInteracted) startAutoScroll(4000);
        }, {
            passive: true
        });
    });
</script>
