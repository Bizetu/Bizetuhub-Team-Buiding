<div class="mx-auto px-4 py-6">

@php
$wysiwygStyling = "prose prose-slate max-w-none
            [&_h2]:text-4xl [&_h2]:mt-3 [&_h2]:mb-3 [&_h2]:text-[#1F3B67] [&_h2]:font-normal
            [&_h3]:text-2xl [&_h3]:mt-5 [&_h3]:mb-3 [&_h3]:text-[#1F3B67] [&_h3]:font-semibold
            [&_h4]:text-xl [&_h4]:mt-4 [&_h4]:mb-3 [&_h4]:text-[#1F3B67] [&_h4]:font-semibold
            [&_h5]:text-xl [&_h5]:mt-3 [&_h5]:mb-2 [&_h5]:text-[#1F3B67] [&_h5]:font-semibold
            [&_h6]:text-lg [&_h6]:mt-2 [&_h6]:mb-2 [&_h6]:text-[#1F3B67] [&_h6]:font-semibold
            [&_p]:text-[#1F3B67] [&_p]:leading-relaxed [&_p]:text-lg [&_p]:mb-3
            [&_strong]:font-semibold [&_strong]:text-[#1F3B67]
            [&_em]:italic [&_em]:text-[#1F3B67]
            [&_u]:underline [&_u]:text-[#1F3B67]
            [&_s]:line-through [&_s]:text-[#1F3B67]/70
            [&_blockquote]:border-l-4 [&_blockquote]:border-[#1F3B67]/30 [&_blockquote]:pl-6
            [&_blockquote]:italic [&_blockquote]:text-[#1F3B67]/80 [&_blockquote]:my-8
            [&_ul]:list-disc [&_ul]:ml-6 [&_ul]:text-[#1F3B67] [&_ul]:mb-3
            [&_ol]:list-decimal [&_ol]:ml-6 [&_ol]:text-[#1F3B67] [&_ol]:mb-3
            [&_li]:my-2 [&_li]:text-[#1F3B67] [&_li]:leading-relaxed
            [&_hr]:border-[#1F3B67]/20 [&_hr]:my-12
            [&_a]:text-[#1F3B67] [&_a]:underline [&_a]:underline-offset-2 [&_a:hover]:text-[#ffb901]
            [&_a:hover]:text-[#1F3B67]/70 [&_a:hover]:transition-colors
            [&_table]:w-full [&_table]:border-collapse [&_table]:my-8 [&_table]:text-lg [&_table]:bg-[#1F3B67]/5
            [&_th]:bg-[#1F3B67] [&_th]:text-white [&_th]:px-4 [&_th]:py-3 [&_th]:text-left [&_th]:font-semibold
            [&_th_h2]:text-white [&_th_h2]:mt-2
            [&_th_h3]:text-white [&_th_h2]:mt-2 
            [&_th_h4]:text-white [&_th_h2]:mt-2
            [&_th_h5]:text-white [&_th_h2]:mt-2 
            [&_th_h6]:text-white [&_th_h2]:mt-2 
            [&_th_p]:text-white [&_th_h2]:mt-2
            [&_td]:px-4 [&_td]:py-3 [&_td]:border [&_td]:border-[#1F3B67]/20 [&_td]:text-[#1F3B67]
            [&_tr:nth-child(even)]:bg-[#1F3B67]/5
            [&_.ql-align-center]:text-center
            [&_.ql-align-right]:text-right
            [&_.ql-align-justify]:text-justify";
@endphp
    @if (!empty($block->children))

        <div class="flex flex-col lg:flex-row gap-6">

            {{-- Left: Slider/Stack of cards --}}
            <div class="relative lg:w-fit px-6 md:px-0">

                {{-- Previous Arrow (mobile only) --}}
                <button id="prev-arrow"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 z-10 bg-white border border-gray-200 rounded-full w-7 h-7 flex items-center justify-center shadow-sm hover:bg-[#FFB901] hover:border-[#FFB901] transition-colors duration-200 disabled:opacity-30 disabled:cursor-not-allowed lg:hidden">
                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Mobile: horizontal slider | Desktop: vertical stack --}}
                <div class="flex gap-1 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-1 lg:flex-col lg:overflow-visible lg:gap-1"
                    id="esg-pillars">
                    @php $index = 0; @endphp
                    @foreach ($block->children as $item)
                        @if ($item->content['publish_status'] ?? false)
                            <div class="pillar-card flex-shrink-0 snap-start flex flex-col rounded-lg cursor-pointer transition-colors duration-300 w-[60vw] sm:w-[40vw] lg:w-full"
                                data-index="{{ $index }}" data-desc="{!! e($item->content['pillar_more_info']) !!}"
                                data-bestpractice="{{ $item->content['best_practice'] ?? '' }}"
                                data-title="{{ $item->content['title'] ?? '' }}"
                                data-image="{{ $item->hasImage('pillar_image_icon') ? $item->image('pillar_image_icon', 'default') : '' }}"
                                data-practiceimage="{{ $item->hasImage('bestpractice_image_icon') ? $item->image('bestpractice_image_icon', 'default') : '' }}">
                                <div
                                    class="flex flex-row items-center gap-2 p-2 rounded pillar-highlight w-full border">
                                    @if ($item->hasImage('pillar_image_icon'))
                                        <img src="{{ $item->image('pillar_image_icon', 'default') }}"
                                            alt="{{ $item->content['title'] ?? 'ESG cover' }}"
                                            class="w-6 h-6 object-cover flex-shrink-0">
                                    @else
                                        <div class="h-6 w-6 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span
                                        class="pillar-title font-bold text-gray-800 text-lg">{!! $item->content['title'] !!}</span>
                                </div>
                            </div>
                            @php $index++; @endphp
                        @endif
                    @endforeach
                </div>

                {{-- Next Arrow (mobile only) --}}
                <button id="next-arrow"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 z-10 bg-white border border-gray-200 rounded-full w-7 h-7 flex items-center justify-center shadow-sm hover:bg-[#FFB901] hover:border-[#FFB901] transition-colors duration-200 disabled:opacity-30 disabled:cursor-not-allowed lg:hidden">
                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            {{-- Right: Description panel --}}
            <div class="lg:w-2/3 lg:mt-0" id="pillar-desc-panel">
                <div class="flex items-center gap-3 mb-3">
                    <img id="pillar-desc-image" src="" alt=""
                        class="w-8 h-8 object-cover flex-shrink-0 hidden">
                    <div id="pillar-desc-icon"
                        class="h-8 w-8 flex items-center justify-center bg-[#1f3b67] flex-shrink-0 hidden">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <h2 id="pillar-desc-title" class="font-bold text-[#1f3b67] text-2xl"></h2>
                </div>
                <div id="pillar-desc-text" class="{{$wysiwygStyling}}"></div>

                {{-- Best Practice section --}}
                <div id="pillar-practice-section" class="flex flex-col items-start gap-3 mt-4 hidden">
                    <div class="flex gap-2 items-center">
                        <img id="pillar-practice-image" src="" alt=""
                            class="w-12 h-12 object-cover flex-shrink-0 hidden">
                        <h2 class="text-2xl text-black font-bold">Best Practice</h2>
                    </div>

                    <div id="pillar-practice-text" class="{{$wysiwygStyling}}"></div>
                </div>
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
    .pdf-link {
        text-decoration: underline;
        color: #1f3b67;
        transition: color 0.2s ease, text-decoration-color 0.2s ease;
        }
    .pdf-link:hover {
        color: #0f2a4d;
        text-decoration-color: #0f2a4d;
        font-weight: bold;
    }
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
        const practiceSection = document.getElementById('pillar-practice-section');
        const slider = document.getElementById('esg-pillars');
        const prevBtn = document.getElementById('prev-arrow');
        const nextBtn = document.getElementById('next-arrow');

        let activeIndex = 0;
        let autoScrollTimer = null;
        let userHasInteracted = false;
        let wordLimit = 300;

        function updateArrows() {
            prevBtn.disabled = activeIndex === 0;
            nextBtn.disabled = activeIndex === cards.length - 1;
        }

        function hasContent(raw) {
            if (!raw || raw === 'null' || raw === '') return false;
            const stripped = raw.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, '').trim();
            return stripped.length > 0;
        }
        function toTitleCase(str) {
            const minorWords = ['a','an','the','and','but','or','for','nor','on','at','to','by','in','of','up','as','is'];
            return str.replace(/\w\S*/g, (word, offset) => {
                if (offset !== 0 && minorWords.includes(word.toLowerCase())) {
                    return word.toLowerCase();
                }
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            });
        }

        function titleCaseHeadings(html) {
            return html.replace(/(<h[1-6][^>]*>)(.*?)(<\/h[1-6]>)/gis, (match, open, content, close) => {
                return `${open}${toTitleCase(content)}${close}`;
            });
        }

        function applyReadMore(element, raw, limit = wordLimit) {
            const processedRaw = titleCaseHeadings(raw); // apply to ALL headings in full content
            const plainText    = processedRaw.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();

            if (plainText.length <= limit) {
                element.innerHTML = processedRaw;
                return;
            }

            const headingMatch = processedRaw.match(/^(<h[1-6][^>]*>)(.*?)(<\/h[1-6]>)/is);
            const headingHTML  = headingMatch
                ? `${headingMatch[1]}${headingMatch[2]}${headingMatch[3]}`
                : '';

            const afterHeading  = headingMatch ? processedRaw.slice(headingMatch[0].length) : processedRaw;
            const bodyPlainText = afterHeading.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
            const truncatedBody = bodyPlainText.substring(0, limit) + '...';

            element.innerHTML = `
                <div class="short-text">${headingHTML}<span>${truncatedBody}</span></div>
                <div class="full-text" style="display:none;">${processedRaw}</div>
                <a href="#" class="read-more-btn" style="color: #1f3b67; margin-left: 4px; display:inline;">Read more</a>
            `;
            element.querySelector('.read-more-btn').addEventListener('click', function(e) {
                e.preventDefault();
                const shortText  = element.querySelector('.short-text');
                const fullText   = element.querySelector('.full-text');
                const isExpanded = fullText.style.display === 'block';

                shortText.style.display = isExpanded ? 'block' : 'none';
                fullText.style.display  = isExpanded ? 'none'  : 'block';
                this.textContent        = isExpanded ? 'Read more' : 'Read less';
            });
        }

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

            const card          = cards[index];
            const image         = document.getElementById('pillar-desc-image');
            const icon          = document.getElementById('pillar-desc-icon');
            const title         = document.getElementById('pillar-desc-title');
            const practiceImage = document.getElementById('pillar-practice-image');
            const practiceText  = document.getElementById('pillar-practice-text');

            // Handle main image
            if (card?.dataset.image) {
                image.src = card.dataset.image;
                image.alt = card.dataset.title ?? '';
                image.removeAttribute('hidden');
                image.classList.remove('hidden');
                icon.classList.add('hidden');
                icon.setAttribute('hidden', '');
            } else {
                image.src = '';
                image.classList.add('hidden');
                icon.classList.remove('hidden');
                icon.removeAttribute('hidden');
            }

            // Handle practice image
            if (card?.dataset.practiceimage) {
                practiceImage.src = card.dataset.practiceimage;
                practiceImage.alt = card.dataset.title ?? '';
                practiceImage.classList.remove('hidden');
                icon.classList.add('hidden');
            } else {
                practiceImage.classList.add('hidden');
                icon.classList.remove('hidden');
            }

            // Handle best practice text with read more
            const raw        = card?.dataset.bestpractice;
            const hasPractice = hasContent(raw);

            if (hasPractice) {
                practiceSection.classList.remove('hidden');
                applyReadMore(practiceText, raw, wordLimit);

                if (card?.dataset.practiceimage) {
                    practiceImage.src = card.dataset.practiceimage;
                    practiceImage.alt = card.dataset.title ?? '';
                    practiceImage.classList.remove('hidden');
                } else {
                    practiceImage.classList.add('hidden');
                    practiceImage.src = '';
                }
            } else {
                practiceSection.classList.add('hidden');
                practiceText.innerHTML = '';
                practiceImage.classList.add('hidden');
                practiceImage.src = '';
            }

            title.textContent    = card?.dataset.title ?? '';
            descText.innerHTML   = card?.dataset.desc ?? '';

            // Scroll into view
            if (window.innerWidth < 1024) {
                card?.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                card?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            updateArrows();

            if (fromUser) {
                userHasInteracted = true;
                stopAutoScroll();
            }
        }

        function startAutoScroll(delay = 50000) {
            if (userHasInteracted) return;
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

        if (cards.length > 0) {
            setActive(0);
            startAutoScroll();
        }

        cards.forEach((card, i) => {
            card.addEventListener('click', () => setActive(i, true));
        });

        slider.addEventListener('mouseenter', stopAutoScroll);
        slider.addEventListener('mouseleave', () => {
            if (!userHasInteracted) startAutoScroll();
        });
        slider.addEventListener('touchstart', stopAutoScroll, { passive: true });
        slider.addEventListener('touchend', () => {
            if (!userHasInteracted) startAutoScroll(4000);
        }, { passive: true });
    });
</script>
