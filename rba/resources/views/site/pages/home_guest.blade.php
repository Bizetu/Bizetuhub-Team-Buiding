@extends('layouts.app')

@php
    $backgroundImage = $pageItem->image('hero_image');
    $bannerSrc = defaultBanner($backgroundImage);
    $isDefault = !$backgroundImage || str_starts_with($backgroundImage, 'data:image/gif;base64');
@endphp

@section('content')
<div class="relative min-h-[87vh] lg:min-h-[89vh] flex items-center lg:items-start">

    <div class="absolute inset-0">
        <picture>
            @if($isDefault)
                <source srcset="{{ asset('images/defaults/banner.webp') }}" type="image/webp">
            @endif
            <img  
                src="{{ $bannerSrc }}" 
                alt="Rwanda Bankers ESG Training Academy"
                class="w-full h-full object-cover object-center md:object-right"
            >
        </picture>
        <div class="absolute inset-0 bg-[#1f3b67]/70 md:hidden"></div>
        <div class="hidden md:block lg:hidden absolute inset-0 
            bg-gradient-to-r 
            from-[#1f3b67]/90 
            via-[#1f3b67]/50 
            to-transparent">
        </div>
        <div class="hidden lg:block absolute inset-0 
            bg-gradient-to-r 
            from-[#1f3b67]/90 from-0% 
            via-[#1f3b67]/50 via-30% 
            via-[#1f3b67]/25 via-55% 
            to-transparent to-100%">
        </div>
    </div>

    {{-- <x-wysiwyg-div :content="$pageItem->description" /> --}}
    <div class="relative z-10 w-full px-4 sm:px-6 lg:px-8 py-8 lg:py-16">
        <div class="max-w-3xl mx-auto text-center lg:text-left force-left mt-32">
            <h1 class="text-white mb-6">
                <span class="block text-2xl sm:text-3xl md:text-4xl mb-2 font-normal">
                    Welcome to the
                </span>
                <span class="block text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-tight">
                    Rwanda Bankers ESG<br>Training Academy.
                </span>
            </h1>
            <p class="text-[#ffb901] text-xl sm:text-xl md:text-2xl font-medium mb-10">
                Driving a profitable, ethical, and inclusive financial ecosystem.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a 
                    href="{{ route('login.choice') }}" 
                    class="inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-4 rounded-full bg-[#ffb901] hover:bg-[#1f3b67] text-[#1f3b67] hover:text-[#ffffff] font-bold text-lg sm:text-xl shadow-lg transition-all duration-300 hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    <x-login-icon />
                    Login to start
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media (min-width: 1024px) {
        .force-left {
            margin-left: 3rem !important;
            margin-right: auto !important;
            text-align: left !important;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .relative.z-10 > div > div > * {
        animation: fadeInUp 0.8s ease-out backwards;
    }

    .relative.z-10 > div > div > *:nth-child(1) {
        animation-delay: 0.1s;
    }

    .relative.z-10 > div > div > *:nth-child(2) {
        animation-delay: 0.3s;
    }

    .relative.z-10 > div > div > *:nth-child(3) {
        animation-delay: 0.5s;
    }

    @media (max-width: 767px) {
        .text-white {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
    }
</style>
@endpush