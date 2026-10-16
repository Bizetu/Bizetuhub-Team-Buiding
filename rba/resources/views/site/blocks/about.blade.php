@if($block)
    @php
        $sections = [];

        $missionTitle = $block->input('about_our_mission');
        $missionDesc  = $block->input('about_our_mission_description');
        $missionIcon  = $block->image('about_our_mission_icon');

        $visionTitle  = $block->input('about_our_vision');
        $visionDesc   = $block->input('about_our_vision_description');
        $visionIcon   = $block->image('about_our_vision_icon');

        $valuesTitle  = $block->input('about_our_values');
        $valuesDesc   = $block->input('about_our_values_description');
        $valuesIcon   = $block->image('about_our_values_icon');

        if ($missionTitle || $missionDesc) {
            $sections[] = [
                'key'   => 'mission',
                'title' => $missionTitle ?: 'Our Mission',
                'icon'  => $missionIcon,
                'body'  => $missionDesc,
            ];
        }

        if ($visionTitle || $visionDesc) {
            $sections[] = [
                'key'   => 'vision',
                'title' => $visionTitle ?: 'Our Vision',
                'icon'  => $visionIcon,
                'body'  => $visionDesc,
            ];
        }

        if ($valuesTitle || $valuesDesc) {
            $sections[] = [
                'key'   => 'values',
                'title' => $valuesTitle ?: 'Our Values',
                'icon'  => $valuesIcon,
                'body'  => $valuesDesc,
            ];
        }

        $uid = 'about-' . $block->id;
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

    <div id="{{ $uid }}" class="mt-2">
        @if (!empty($sections))
            <div class="flex flex-col md:flex-row gap-8 items-start" data-about-tabs="{{ $uid }}">
                <div class="w-full md:hidden overflow-hidden" data-about-carousel>
                    <nav
                        role="tablist"
                        aria-label="About sections"
                        class="flex gap-3 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-2"
                    >
                        @foreach ($sections as $i => $section)
                            <button
                                type="button"
                                role="tab"
                                aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                                aria-controls="{{ $uid }}-panel-{{ $section['key'] }}"
                                data-panel="{{ $uid }}-panel-{{ $section['key'] }}"
                                class="about-tab-btn shrink-0 snap-start min-w-[220px] flex items-center gap-3 p-3 rounded-md text-left font-semibold transition-all duration-200 text-lg
                                {{ $i === 0 ? 'bg-[#FFB901] text-[#1F3B67]' : 'border text-[#1F3B67]' }}"
                            >
                                @if ($section['icon'])
                                    <img src="{{ $section['icon'] }}" class="w-6 h-6 shrink-0 object-contain" alt="">
                                @endif
                                <span>{{ $section['title'] }}</span>
                            </button>
                        @endforeach
                    </nav>
                </div>

                <nav
                    role="tablist"
                    aria-label="About sections"
                    class="hidden md:flex w-full md:w-[260px] shrink-0 flex-col gap-2"
                >
                    @foreach ($sections as $i => $section)
                        <button
                            type="button"
                            role="tab"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                            aria-controls="{{ $uid }}-panel-{{ $section['key'] }}"
                            data-panel="{{ $uid }}-panel-{{ $section['key'] }}"
                            class="about-tab-btn w-full flex items-center gap-3 p-2 rounded-md text-left font-bold transition-all duration-200 text-lg
                            {{ $i === 0 ? 'bg-[#FFB901] text-[#1F3B67]' : 'border text-[#1F3B67]' }}"
                        >
                            @if ($section['icon'])
                                <img src="{{ $section['icon'] }}" class="w-6 h-6 shrink-0 object-contain" alt="">
                            @endif
                            <span>{{ $section['title'] }}</span>
                        </button>
                    @endforeach
                </nav>

                <div class="flex-1 min-w-0 w-full">
                    @foreach ($sections as $i => $section)
                        <div
                            id="{{ $uid }}-panel-{{ $section['key'] }}"
                            role="tabpanel"
                            aria-hidden="{{ $i === 0 ? 'false' : 'true' }}"
                            class="about-tab-panel {{ $i === 0 ? 'block' : 'hidden' }}"
                        >
                            <div class="flex items-center gap-3 mb-2">
                                @if ($section['icon'])
                                    <img src="{{ $section['icon'] }}" class="w-7 h-7 object-contain" alt="">
                                @endif

                                <h3 class="text-2xl font-bold text-[#1F3B67]">
                                    {{ $section['title'] }}
                                </h3>
                            </div>

                            @if ($section['body'])
                                <div class="text-[#1F3B67] text-base prose max-w-none prose-p:mb-4 prose-li:mb-1 {{ $wysiwygStyling }}">
                                    {!! $section['body'] !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('[data-about-tabs]').forEach(function(wrapper) {

            const buttons = wrapper.querySelectorAll('.about-tab-btn');
            const panels  = wrapper.querySelectorAll('.about-tab-panel');

            function activateTab(btn) {

                buttons.forEach(function(b) {
                    b.setAttribute('aria-selected', 'false');
                    b.classList.remove('bg-[#FFB901]');
                    b.classList.add('border');
                });

                panels.forEach(function(p) {
                    p.classList.add('hidden');
                    p.classList.remove('block');
                    p.setAttribute('aria-hidden', 'true');
                });

                btn.setAttribute('aria-selected', 'true');
                btn.classList.remove('border');
                btn.classList.add('bg-[#FFB901]');

                const target = document.getElementById(btn.dataset.panel);

                if (target) {
                    target.classList.remove('hidden');
                    target.classList.add('block');
                    target.setAttribute('aria-hidden', 'false');
                }

                btn.scrollIntoView({
                    behavior: 'smooth',
                    inline: 'center',
                    block: 'nearest'
                });
            }

            buttons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    activateTab(btn);
                });
            });

        });
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endif
