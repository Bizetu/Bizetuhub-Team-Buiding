@extends('layouts.app')

@section('title', 'FAQs - Rwanda Bankers Association')

@section('content')

    <!-- Hero Section -->
    <x-hero 
        title="{{ $pageItem->header_title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'FAQs', 'url' => $pageItem->key],
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <!-- Main Content -->
    <section class="py-2"
        x-data="{ 
            activeTab: '{{ Str::slug($groups->keys()->first()) }}',
            openFaq: null,
            setTab(tab) {
                this.activeTab = tab;
                this.openFaq = null;
            },
            toggleFaq(id) {
                this.openFaq = this.openFaq === id ? null : id;
            }
        }"
    >
        <div class="max-w-7xl mx-auto min-h-[300px]">

            <div class="border-b border-gray-200 w-full">
                <nav class="flex justify-center gap-x-4 sm:gap-x-12 overflow-x-auto max-w-6xl mx-auto">
                    @foreach($groups as $categoryName => $categoryFaqs)
                        <button
                            type="button"
                            title="View {{ $categoryName }} FAQs"
                            @click="setTab('{{ Str::slug($categoryName) }}')"
                            :class="activeTab === '{{ Str::slug($categoryName) }}'
                                ? 'border-[#1f3b67] text-[#1f3b67] font-bold '
                                : 'border-transparent text-[#1f3b67] hover:font-bold hover:border-[#1f3b67]'"
                            class="border-b-2 py-2 px-1 text-base md:text-xl font-medium whitespace-nowrap transition-colors duration-150"
                        >
                            {{ $categoryName }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- FAQ Content Panels -->
            @foreach($groups as $categoryName => $categoryFaqs)
                <div
                    x-show="activeTab === '{{ Str::slug($categoryName) }}'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <div class="space-y-px border-t border-gray-200">
                        @foreach($categoryFaqs as $faq)
                            <div class="border-b border-gray-200 group">
                                <button
                                    type="button"
                                    class="w-full p-3 flex justify-between items-start text-left hover:bg-gray-100 transition-colors duration-150"
                                    @click="toggleFaq({{ $faq->id }})"
                                    :aria-expanded="openFaq === {{ $faq->id }}"
                                >
                                    <span class="text-base md:text-xl sm:text-lg font-medium text-[#1F3B67] pr-4 leading-snug">
                                        {{ $faq->title }}
                                    </span>
                                    <svg
                                        :class="openFaq === {{ $faq->id }} ? 'rotate-180' : ''"
                                        class="w-5 h-5 sm:w-6 sm:h-6 text-[#1F3B67] flex-shrink-0 transition-transform duration-200"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div
                                    x-show="openFaq === {{ $faq->id }}"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                >
                                    <div class="text-base md:text-xl text-[#1F3B67] leading-relaxed">
                                        @php
                                            $imageUrl = $faq->image('faq_image');
                                            $hasImage = $imageUrl && !str_contains($imageUrl, 'data:image/gif;base64');
                                        @endphp

                                        @if($hasImage)
                                            <div x-data="{ open: false, img: '' }" class="p-5 grid md:grid-cols-[1fr_auto] gap-4 md:gap-6">
                                                <x-wysiwyg-div :content="$faq->answer"/>
                                                <div class="md:w-64 lg:w-80 flex-shrink-0">
                                                    <img 
                                                        src="{{ $imageUrl }}" 
                                                        alt="{{ $faq->title }}" 
                                                        @click="img = '{{ $imageUrl }}'; open = true"
                                                        class="w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition"
                                                    />
                                                </div>
                                                <div 
                                                    x-show="open"
                                                    x-transition
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                                                    @click.self="open = false"
                                                    style="display: none;"
                                                >
                                                    <div @click.outside="open = false" class="relative max-w-3xl w-full">
                                                        <button @click="open = false" class="absolute top-2 right-2 text-white text-2xl z-10 bg-[#1f3b67] rounded-full w-10 h-10 flex items-center justify-center hover:bg-[#1f3b67]/90 transition">
                                                            ✕
                                                        </button>
                                                        <img :src="img" class="w-full h-auto rounded-lg shadow-xl"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-4">
                                                <x-wysiwyg-div :content="$faq->answer"/>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </section>

@endsection