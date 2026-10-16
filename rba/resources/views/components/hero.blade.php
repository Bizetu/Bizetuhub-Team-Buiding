@props([
    'title' => '',
    'subtitle' => '',
    'breadcrumbs' => [],
    'backgroundImage' => null,
    'height' => 'h-75',
])

<section class="relative h-48 lg:h-64 overflow-hidden mt-20">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <picture>
            @php
                $bannerSrc = defaultBanner($backgroundImage);
                $isDefault = !$backgroundImage || str_starts_with($backgroundImage, 'data:image/gif;base64');
            @endphp

            @if($isDefault)
                <source srcset="{{ asset('images/defaults/banner.webp') }}" type="image/webp">
            @endif

            <img
                src="{{ $bannerSrc }}"
                alt=""
                width="1920"
                height="600"
                fetchpriority="high"
                decoding="sync"
                loading="eager"
                class="w-full h-full object-cover"
            >
        </picture>
    </div>

    <div class="relative z-10 h-48 lg:h-64 flex items-center">
        <div class="max-w-full md:max-w-[50vw] mr-auto pt-12 ml-4 md:ml-12">
            @if($title)
                <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-4 tracking-tight">
                    {{ $title }}
                </h1>
            @endif

            @if($subtitle)
                <p class="text-xl md:text-xl text-white/90 font-light">
                    {{ $subtitle }}
                </p>
            @endif
            @if(count($breadcrumbs) > 0)
                <nav class="my-4" aria-label="Breadcrumb">
                    <ol class="flex items-center text-lg">
                        @foreach($breadcrumbs as $index => $breadcrumb)
                            <li class="flex items-center">
                                @if($index > 0)
                                    <svg class="w-4 h-4 text-white/60 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                @endif

                                @if(isset($breadcrumb['url']))
                                    <a
                                        href="{{ $breadcrumb['url'] }}"
                                        class="text-white/80 hover:text-white hover:scale-105 transition-all duration-200 font-medium"
                                    >
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                @else
                                    <span class="text-white font-medium">
                                        {{ $breadcrumb['label'] }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>
    </div>
</section>
