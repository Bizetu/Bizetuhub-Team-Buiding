@extends('layouts.app')

@section('content')

    <section class="relative h-[56vh] md:h-[83vh] lg:h-[84vh] xl:h-[87vh] overflow-hidden">
        @foreach($pageItem->blocks as $block)
            @if($block->type === 'slideshow')
                @includeIf('site.blocks.' . $block->type, ['block' => $block])
            @endif
        @endforeach
    </section>

    <section class="py-2 bg-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 md:grid-cols-3 gap-8 text-center">
                <div class="animate-[fadeInUp_0.6s_ease-out_both]">
                    <h3 class="text-2xl md:text-6xl font-bold md:font-black mb-2">12</h3>
                    <p class="text-sm md:text-lg uppercase md:tracking-widest">Banks</p>
                </div>
                <div class="animate-[fadeInUp_0.6s_ease-out_0.15s_both]">
                    <h3 class="text-2xl md:text-6xl font-bold md:font-black mb-2">8</h3>
                    <p class="text-sm md:text-lg uppercase md:tracking-widest">Training Modules</p>
                </div>
                <div class="animate-[fadeInUp_0.6s_ease-out_0.3s_both]">
                    <h3 class="text-2xl md:text-6xl font-bold md:font-black mb-2">6</h3>
                    <p class="text-sm md:text-lg uppercase md:tracking-widest">ESG Guidelines</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-0 w-full">
        <div class="bg-[#1f3b67] border-b border-[#1f3b67] w-full">
            <div class="flex justify-center max-w-6xl mx-auto">

                <button onclick="switchTab('ceo')" id="tab-ceo"
                    class="tab-btn group flex items-center gap-2 border-b-2 border-transparent py-3 px-3 sm:px-6 text-base md:text-lg font-medium transition-all duration-200 flex-1 justify-center md:flex-none md:justify-start text-white hover:text-[#ffb901]">
                    <span class="icon-active hidden"><x-yellow_word_from_the_ceo_icon /></span>
                    <span class="icon-inactive"><x-white_word_from_the_ceo_icon /></span>
                    <span class="hidden sm:inline">Word from the CEO</span>
                    <span class="sm:hidden">CEO</span>
                </button>

                <button onclick="switchTab('vision')" id="tab-vision"
                    class="tab-btn group flex items-center gap-2 border-b-2 border-transparent py-3 px-3 sm:px-6 text-base md:text-lg font-medium transition-all duration-200 flex-1 justify-center md:flex-none md:justify-start text-white hover:text-[#ffb901]">
                    <span class="icon-active hidden"><x-yellow_green_growth_icon /></span>
                    <span class="icon-inactive"><x-white_green_growth_icon /></span>
                    <span class="hidden sm:inline">Rwanda's Green Growth &amp; Sustainability Vision</span>
                    <span class="sm:hidden">Green Vision</span>
                </button>

            </div>
        </div>

        <div id="panel-ceo" class="tab-panel max-w-7xl mx-auto">
            <div class="relative px-4 sm:px-6 md:px-8 overflow-hidden">
                @foreach($pageItem->blocks as $block)
                    @if($block->type === 'image_next_to_text')
                        @includeIf('site.blocks.' . $block->type, ['block' => $block])
                    @endif
                @endforeach
            </div>
        </div>

        <div id="panel-vision" class="tab-panel max-w-7xl mx-auto hidden">
            <div class="relative px-4 sm:px-6 md:px-8 overflow-hidden">
                @foreach($pageItem->blocks as $block)
                    @if($block->type === 'rwandas_green_growth')
                        @includeIf('site.blocks.' . $block->type, ['block' => $block])
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <script>
        function switchTab(tab) {
            const panels = document.querySelectorAll('.tab-panel');
            const tabs = {
                ceo: document.getElementById('tab-ceo'),
                vision: document.getElementById('tab-vision')
            };

            panels.forEach(p => p.classList.add('hidden'));
            document.getElementById('panel-' + tab).classList.remove('hidden');

            Object.values(tabs).forEach(t => {
                t.classList.remove('text-[#FFB901]', 'border-[#FFB901]');
                t.classList.add('text-white', 'border-transparent');

                const activeIcon = t.querySelector('.icon-active');
                const inactiveIcon = t.querySelector('.icon-inactive');

                if (activeIcon) activeIcon.classList.add('hidden');
                if (inactiveIcon) inactiveIcon.classList.remove('hidden');
            });

            const activeTab = tabs[tab];
            activeTab.classList.remove('text-white', 'border-transparent');
            activeTab.classList.add('text-[#FFB901]', 'border-[#FFB901]');

            const activeIcon = activeTab.querySelector('.icon-active');
            const inactiveIcon = activeTab.querySelector('.icon-inactive');

            if (activeIcon) activeIcon.classList.remove('hidden');
            if (inactiveIcon) inactiveIcon.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => switchTab('ceo'));
    </script>

    <section class="py-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-black mb-12">Courses</h2>

            <div class="flex overflow-x-auto gap-4 pb-4 scrollbar-hide">
                @foreach ($featuredModules as $module)
                <div class="flex-none w-48 sm:w-56 md:w-64">
                    <div class="relative aspect-square mb-3 overflow-hidden">
                        <img
                            src="{{ $module->image_with_fallback }}"
                            alt="{{ $module->display_name }} course image"
                            class="w-full h-full object-cover transition-all duration-300 hover:scale-110"
                            loading="lazy"
                            decoding="async"
                        >
                        <a href="{{ route('courses.detail', $module->short_name) }}" class="absolute bottom-3 right-3 inline-block secondary-yellow-button">
                            Learn More →
                        </a>
                    </div>
                    <p class="font-bold text-lg sm:text-lg">{{ $module->display_name ?? $module->short_name ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-0">
        <div class="relative h-80 md:h-96 bg-cover bg-center bg-no-repeat overflow-hidden"
            style="background-image: url({{ asset('images/defaults/resources-n-publications.webp') }});">
            <div class="h-full flex items-center px-4 md:px-16 py-6 md:py-0">
                <div class="bg-white p-3 md:p-8 max-w-md md:max-w-lg">
                    <h2 class="text-2xl md:text-4xl font-black mb-3 md:mb-4">Resources and Publications</h2>
                    <p class="text-base md:text-lg mb-4 md:mb-6">
                        Access reports, research, events, and learning materials that support informed decision-making and sustainable financial sector development.
                    </p>
                    <a href="{{ route('events') }}" class="inline-block yellow-button-rounded-full text-base md:text-lg">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-partners />
@endsection
