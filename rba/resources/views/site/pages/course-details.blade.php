@extends('layouts.app')

@section('title', 'Courses - Rwanda Bankers Association')

@php
    $configLms = config()->get('settings.lms.live');
@endphp

@section('content')

    <x-hero title="{{ $course->display_name ?? $pageItem->header_title }}" :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Courses', 'url' => $pageItem->key]]" :backgroundImage="$pageItem->image('hero_image')"
        height="h-80" />

    <section class="min-h-screen bg-gray-50">
        <div class="max-w-8xl mx-auto px-10">
            <div class="grid grid-cols-1 md:grid-cols-6 p-6 gap-4">

                <div class="col-span-1 flex flex-col gap-y-3">
                    <div><span class="font-bold">Started:</span> 10th March 2025</div>
                    <div><span class="font-bold">Effort:</span> 45 minutes</div>
                    @if ($enrolled)
                        <a class="bg-[#FFB901] text-[#1F3B67] px-4 py-2 self-start"
                            href="{{ $configLms['LMS_BASE'] . '/courses/' . $course->course_id . '/courseware' }}">Take
                            Module</a>
                    @else
                        <a class="bg-[#1F3B67] text-white px-4 py-2 self-start"
                            href="{{ route('courses.enroll', $course->course_id) }}">Enroll into Course </a>
                    @endif
                </div>

                <div class="md:col-span-5 flex flex-col gap-4" x-data="{ activeTab: 'description' }">

                    {{-- Tabs --}}
                    <div class="flex flex-row gap-4">
                        <button @click="activeTab = 'description'"
                            :class="activeTab === 'description' ? 'border-2 border-[#FFB901] font-bold' : 'font-bold'"
                            class="px-4 py-2">
                            Description
                        </button>
                        <button @click="activeTab = 'outline'"
                            :class="activeTab === 'outline' ? 'border-2 border-[#FFB901] font-bold' : 'font-bold'"
                            class="px-4 py-2">
                            Course Outline
                        </button>
                    </div>

                    {{-- Description Tab --}}
                    <div x-show="activeTab === 'description'" class="border border-gray-900 p-4">
                        <x-wysiwyg-div :content="$course->overview"/>
                    </div>

                    {{-- Course Outline Tab --}}
                    <div x-show="activeTab === 'outline'" class="border border-gray-900 p-4">
                        <div style="color: #374151;">
                            <x-wysiwyg-div :content="$course->more_info"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
