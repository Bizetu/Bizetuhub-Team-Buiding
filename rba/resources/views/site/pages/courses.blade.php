@extends('layouts.app')

@section('title', 'Courses - Rwanda Bankers Association')

@section('content')

@php

    $configLms = config()->get('settings.lms.live');

@endphp

    <x-hero title="{{ $pageItem->header_title }}" :breadcrumbs="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $pageItem->header_title, 'url' => $pageItem->key],
    ]" :backgroundImage="$pageItem->image('hero_image')" height="h-80" />

    <section class="min-h-screen bg-gray-50">
        <div class="max-w-8xl mx-auto px-10">
            <div class="grid grid-cols-1 md:grid-cols-12 my-4 gap-4">

                <div class="md:col-span-3 lg:col-span-2">
                    <div class="w-full border rounded py-2 sticky top-1 px-3">

                        <div class="flex flex-col items-center mb-4">
                            <img src="{{ asset('images/defaults/profile-icon.svg') }}" alt="Profile" class="w-12 h-12 mb-2">
                            <a href="{{ route('profile.progress') }}" class="text-base text-[#1F3B67] hover:underline"> <img
                                    src="{{ asset('images/defaults/Eye-icon.svg') }}" alt="Profile"></a>
                        </div>

                        <div class="border-t pt-3 pb-3">
                            <ul class="space-y-3">
                                <li class="flex justify-between md:text-base text-lg text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ asset('images/defaults/completed-icon.svg') }}" alt="Profile"
                                            class="w-4 h-4">
                                        <span>Attempted</span>
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $countAttempted }}</span>
                                </li>
                                <li class="flex justify-between md:text-base text-lg text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ asset('images/defaults/completed-icon.svg') }}" alt="Profile"
                                            class="w-4 h-4">
                                        <span>Completed</span>
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $countCompleted }}</span>
                                </li>
                                <li class="flex justify-between md:text-base text-lg text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ asset('images/defaults/badge-icon.svg') }}" alt="Profile"
                                            class="w-4 h-4">
                                        <span>Badges Earned</span>
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $countCompleted }}</span>
                                </li>
                                <li class="flex justify-between md:text-base text-lg text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ asset('images/defaults/clock-icon.svg') }}" alt="Profile"
                                            class="w-4 h-4">
                                        <span>Training Hours</span>
                                    </div>

                                    <span class="font-bold text-gray-800">{{ $totalHours }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex flex-col justify-between items-center">
                                <a href="{{ route('profile.downloadProgress') }}"
                                    class="inline-flex items-center p-2 border rounded-xl border-gray-600 text-white bg-[#1F3B67] hover:text-white gap-2">
                                    <img src="{{ asset('images/defaults/download-icon.svg') }}" alt="Profile">
                                    Download Progress
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Main content --}}
                <div class="md:col-span-9 lg:col-span-10">

                <div class="flex justify-between pb-4">
                    <form action="{{ route('courses') }}" method="GET"
                        x-data="{ timer: null }"
                        x-on:change="if($event.target.tagName !== 'INPUT') $el.submit()"
                        class="w-full">

                        <div class="flex flex-col md:flex-row items-stretch gap-2 w-full">

                            <div class="w-full md:flex-1">
                                <x-select id="course_id" name="course_id"
                                    placeholder="Filter by Course"
                                    :options="$coursesList"
                                    :selected="request('course_id')"
                                    class="rounded-none !py-[13px] w-full h-full" />
                            </div>

                            <div class="w-full md:flex-1">
                                <x-input id="course_name" name="course_name"
                                    placeholder="Search for course"
                                    :value="request('course_name')"
                                    x-on:input="
                                        clearTimeout(timer);
                                        timer = setTimeout(() => $el.closest('form').submit(), 1000)
                                    "
                                    autocomplete="off"
                                    class="rounded-none !py-[13px] w-full h-full" />
                            </div>

                            <div class="flex gap-2 w-full md:w-auto">

                            <x-secondary-button type="submit"
                                class="flex-1 md:flex-none rounded-none !rounded-none !py-[13px] !px-6 text-lg shadow !bg-[#1F3B67] !text-white whitespace-nowrap">
                                Search
                            </x-secondary-button>

                            <x-secondary-button type="button"
                                x-on:click="window.location.href='{{ route('courses') }}'"
                                class="flex-1 md:flex-none rounded-none !rounded-none !py-[13px] !px-6 text-lg shadow !bg-white !text-[#1F3B67] !border !border-[#1F3B67] whitespace-nowrap">
                                Reset
                            </x-secondary-button>

                        </div>

                        </div>
                    </form>
                </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        @forelse ($courses as $course)
                            @php $license = $userLicenseMap->get($course->id) @endphp

                            <div class="rounded-none overflow-hidden shadow-lg bg-white items-end relative pb-10">
                                <a href="{{ route('courses.detail', $course->short_name) }}">
                                    <div class="relative">
                                        <img class="w-full h-[14rem] object-cover" src="{{ $course->image_with_fallback }}" alt="Course Thumbnail">
                                        
                                        <!-- Clock overlay on image -->
                                        <div class="absolute bottom-2 left-2 flex items-center gap-1 bg-white text-black text-base px-2 py-1 rounded">
                                            <img class="w-3 h-3" src="{{ asset('images/defaults/clock-courses-icon.svg') }}" alt="Clock">
                                            <span>{!! $course->course_effort . ' min' !!}</span>
                                        </div>
                                    </div>
                                </a>

                                <div class="px-4 py-3">
                                    <a class="font-bold text-lg mb-1 text-black hover:text-[#1F3B67] border-b-2 border-transparent hover:border-[#1F3B67] transition-colors duration-150"
                                        href="{{ route('courses.detail', $course->short_name) }}">{{ $course->display_name }}</a>
                                </div>

                                <div class="px-2 pb-3 flex justify-end absolute bottom-1 right-2">
                                    @if ($license)
                                        @if ($license->score > 79)
                                            <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $course->course_id . '/courseware' }}"
                                                class="inline-flex items-center px-3 py-1 border border-gray-600 text-[#1F3B67] bg-[#FFB901] hover:text-[#1F3B67]">
                                                Completed
                                            </a>
                                        @elseif($license->score > 20)
                                            <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $course->course_id . '/courseware' }}"
                                                class="inline-flex items-center px-3 py-1 border bg-[#D9D9D9] text-[#1F3B67] hover:text-[#1F3B67]">
                                                Retake
                                            </a>
                                        @else
                                            <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $course->course_id . '/courseware' }}"
                                                class="inline-flex items-center px-3 py-1 text-white bg-[#1F3B67] hover:text-black">
                                                Take Course
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('courses.enroll', $course->course_id) }}"
                                            class="inline-flex items-center px-3 py-1 border border-gray-600 text-white bg-[#1F3B67] hover:text-black">
                                            Enroll to Start
                                        </a>
                                    @endif


                                </div>
                            </div>

                        @empty
                            <div class="md:col-span-4 flex flex-col items-center justify-center py-10 text-gray-500">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-semibold text-lg">No courses found</p>
                                <p class="text-lg">Try adjusting your search or reset the filters</p>
                            </div>
                        @endforelse


                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
