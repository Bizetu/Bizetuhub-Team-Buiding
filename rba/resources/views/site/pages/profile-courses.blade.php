@extends('layouts.app')

@section('title', 'My Enrolled Modules - Rwanda Bankers Association')

@section('content')

    @php

        $configLms = config()->get('settings.lms.live');

    @endphp

@section('hero_image', asset('images/defaults/banner.webp'))

<x-hero title="{{ $pageItem->header_title }}" :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => $pageItem->header_title, 'url' => $pageItem->key]]" :backgroundImage="$pageItem->image('hero_image')" height="h-80" />

<x-profile-nav-tabs active="profileCourses" />

<div class="vm-4 px-4">
    <div class="max-w-8xl mx-auto">
                <div class="flex justify-between pb-4">
                    <form action="{{ route('profile.courses') }}" method="GET"
                        x-data="{ timer: null }"
                        x-on:change="if($event.target.tagName !== 'INPUT') $el.submit()"
                        class="w-full">

                        <div class="flex flex-col md:flex-row items-stretch gap-2 w-full">

                            <div class="w-full md:flex-1">
                                <x-select id="status" name="status"
                                    placeholder="Filter by Status"
                                    :options="$completionStatuses"
                                    :selected="request('status')"
                                    class="rounded-none !py-[13px] w-full h-full" />
                            </div>

                            <div class="w-full md:flex-1">
                                <x-select id="mandatory_status" name="mandatory_status"
                                    placeholder="Filter by Mandatory Status"
                                    :options="$mandatoryStatuses"
                                    :selected="request('mandatory_status')"
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
                                x-on:click="window.location.href='{{ route('profile.courses') }}'"
                                class="flex-1 md:flex-none rounded-none !rounded-none !py-[13px] !px-6 text-lg shadow !bg-white !text-[#1F3B67] !border !border-[#1F3B67] whitespace-nowrap">
                                Reset
                            </x-secondary-button>

                        </div>

                        </div>
                    </form>
                </div>
        <div class="bg-gray-1 min-h-80 px-0">

            @forelse ($licenses as $license)
                <div class="bg-gray-200 my-2 grid grid-cols-1 md:grid-cols-12 gap-2 border-1 border-[#1F3B67]">

                    <div class="md:col-span-3">
                        {{-- <img src="{{ $license->course->course_image_uri }}" class="w-full max-h-49 object-cover" /> --}}
                        <img src="{{ $license->course->image_with_fallback }}" class="w-full h-[14rem] object-cover" />
                    </div>

                    <div class="md:col-span-9 bg-gray-300 p-3 space-y-2">

                        {{-- Title + desktop buttons --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-2 py-1">
                            <div class="md:col-span-6 font-bold text-xl">
                                <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $license->course->course_id . '/courseware' }}" class="hover:underline hover:text-[#1F3B67]">
                                    {{ $license->course->display_name }}
                                </a>
                            </div>
                            <div class="hidden md:block md:col-span-3">
                                <button class="flex items-center space-x-3 focus:outline-none group">
                                    <span class="text-lg font-bold border-1 border-[#1F3B67] p-2 px-3">
                                        Status: {{ $license->status }}
                                    </span>
                                </button>
                            </div>
                            <div class="hidden md:block md:col-span-3">
                                @if ($license->score > 79)
                                    @if($license->evaluation_status)
                                        <a href="{{route('profile.certificateSingleModule', $license->course_id) }}" class="flex items-center focus:outline-none group">
                                            <span class="text-lg font-medium bg-[#FFB901] text-[#1F3B67] p-2 px-3  w-full flex justify-center">
                                                Download Badge
                                            </span>
                                        </a>
                                    @else
                                        <a href="{{route('course.evaluate', $license->course_id) }}" class="flex items-center focus:outline-none group">
                                            <span class="text-lg font-medium bg-[#FFB901] text-[#1F3B67] p-2 px-3 w-full flex justify-center">
                                                Evaluate and Download Badge
                                            </span>
                                        </a>
                                    @endif


                                @elseif($license->score > 20 && $license->score < 79)
                                    <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $license->course->course_id . '/courseware' }}"
                                        class="flex items-center focus:outline-none group">
                                        <span class="text-lg font-medium bg-[#1F3B67] text-white p-2 px-3 w-full flex justify-center">
                                            Retake Course
                                        </span>
                                    </a>
                                @else
                                    <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $license->course->course_id . '/courseware' }}"
                                        class="flex items-center focus:outline-none group">
                                        <span class="text-lg font-medium bg-[#1F3B67] text-white p-2 px-3 w-full flex justify-center">
                                            Continue with Course
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @php
                            $totalEnrolled = $license->total_enrolled;
                            $totalCompleted = $license->totalCompleted;

                            $enrolledText =
                                $totalEnrolled .
                                ' ' .
                                'people have enrolled';

                            if ($totalEnrolled < 2) {
                                $enrolledText =
                                    $totalEnrolled .
                                    ' ' .
                                    'person has enrolled';
                                if ($totalEnrolled < 1) {
                                    $enrolledText =
                                        'no person has enrolled';
                                }
                            }

                            $completedText =
                                $totalCompleted .
                                ' ' .
                                'people have been certified';

                            if ($totalCompleted < 2) {
                                $completedText =
                                    $totalCompleted .
                                    ' ' .
                                    'person has been certified';
                                if ($totalCompleted < 1) {
                                    $completedText =
                                        'no person has been certified';
                                }
                            }
                        @endphp
                        <div class="grid grid-cols-1">
                            <p class="text-base text-black">{{ $enrolledText }} | {{ $completedText }}</p>
                        </div>

                        {{-- Mobile : icon rows will show side by side --}}
                        <div class="block md:hidden space-y-2 text-lg text-gray-700">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('images/defaults/calendarIcon.svg') }}" alt="">
                                <span>Enrollment Date: <strong>{{ $license->date_created }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('images/defaults/calendarIcon.svg') }}" alt="">
                                <span>Course Completion Date: <strong>{{ $license->date_completed }}</strong></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <img src="{{ asset('images/defaults/Tick.svg') }}" alt="" class="w-4 h-4">
                                <span>Current Score: <strong>{{ $license->score . '%' }}</strong></span>
                            </div>
                        </div>

                        {{-- Mobile : full-width buttons --}}
                        <div class="flex flex-col gap-2 md:hidden pt-1">
                            <button
                                class="w-full text-lg font-medium border-2 border-[#1F3B67] text-[#1F3B67] px-4 py-2 ">
                                {{ $license->status }}
                            </button>

                            @if ($license->score > 79)
                                <button class="w-full text-lg font-medium bg-[#FFB901] text-[#1F3B67] px-4 py-2 ">
                                    <a href="">Download Badge</a>
                                </button>
                            @elseif($license->score > 20 && $license->score < 80)
                                <button class="w-full text-lg font-medium bg-[#1F3B67] text-white px-4 py-2 ">
                                    <a
                                        href="{{ $configLms['LMS_BASE'] . '/courses/' . $license->course->course_id . '/courseware' }}">Retake
                                        Course</a>
                                </button>
                            @else
                                <button class="w-full text-lg font-medium bg-[#1F3B67] text-white px-4 py-2 ">
                                    <a
                                        href="{{ $configLms['LMS_BASE'] . '/courses/' . $license->course->course_id . '/courseware' }}">Continue
                                        with Courses</a>
                                </button>
                            @endif

                        </div>

                        {{-- Desktop grid icons 4-column label row --}}
                        <div class="hidden md:grid md:grid-cols-12 gap-2 py-1 font-semibold text-lg">
                            <div class="md:col-span-3">Enrollment Date</div>
                            <div class="md:col-span-3">Completion Date</div>
                            <div class="flex md:col-span-3 gap-1">
                                <img src="{{ asset('images/defaults/Tick.svg') }}" alt="" class="w-4 h-4">
                                Previous Score
                            </div>
                            <div class="flex md:col-span-3 gap-1">
                                <img src="{{ asset('images/defaults/Tick.svg') }}" alt="" class="w-4 h-4">
                                Current Score
                            </div>
                        </div>

                        {{-- Desktop grid icons 4-column value row --}}
                        <div class="hidden md:grid md:grid-cols-12 gap-2 py-1 text-lg">
                            <div class="flex md:col-span-3 gap-2">
                                <img src="{{ asset('images/defaults/calendarIcon.svg') }}" alt="">
                                {{ $license->date_created }}
                            </div>
                            <div class="flex md:col-span-3 gap-2">
                                <img src="{{ asset('images/defaults/calendarIcon.svg') }}" alt="">
                                {{ $license->date_completed }}
                            </div>
                            <div class="md:col-span-3 gap-2">
                                <span><strong>0%</strong></span>
                            </div>
                            <div class="flex md:col-span-3 gap-2">
                                <span><strong>{{ $license->score . '%' }}</strong></span>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-10 text-gray-500">
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


@endsection
