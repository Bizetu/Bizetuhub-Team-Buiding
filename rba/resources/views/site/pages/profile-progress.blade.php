@extends('layouts.app')

@section('title', 'My Course Progress - Rwanda Bankers Association')

@section('content')

@section('hero_image', asset('images/defaults/banner.webp'))

<x-hero title="{{ $pageItem->header_title }}" :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => $pageItem->header_title, 'url' => $pageItem->key]]" :backgroundImage="$pageItem->image('hero_image')" height="h-80" />

<x-profile-nav-tabs active="profileProgress" />

<div class="w-full px-4 py-2 max-w-8xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4 mb-3">
        <!-- Left Content -->
        <div>
            <h2 class="text-2xl font-bold text-[#1F3B67]">My Progress</h2>
            <p class="text-lg text-gray-700 mt-1">
                Track course completion progress
            </p>
        </div>

        <!-- Right Button -->
        <div class="md:justify-self-end">
            <a href="{{ route('profile.downloadProgress') }}" class="border rounded-lg p-2 inline-flex items-center text-white bg-[#1F3B67] w-full md:w-auto justify-center">
                <img src="{{ asset('images/defaults/download-icon.svg') }}" alt="Download">
                <p class="pl-2 text-white">Progress Snapshot</p>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col md:flex-row gap-2">
        @foreach ([
            ['filter' => 'all',                 'label' => 'All Modules',           'count' => $stats['all']],
            ['filter' => 'completed',           'label' => 'Completed',             'count' => $stats['completed']],
            ['filter' => 'failed',              'label' => 'Failed',                'count' => $stats['failed']],
            // ['filter' => 'mandatory_completed', 'label' => 'Mandatory Completed',   'count' => $stats['mandatory_completed']],
            // ['filter' => 'mandatory_all',       'label' => 'My Compulsory Modules', 'count' => $stats['mandatory_all']],
        ] as $btn)
            <button
                data-filter="{{ $btn['filter'] }}"
                class="filter-btn rounded-none py-2 px-2 text-lg shadow w-full md:flex-1 transition-colors
                       {{ $loop->first ? 'bg-[#1F3B67] text-white' : 'bg-[#1F3B67]/60 text-white' }}">
                <span class="flex items-center justify-between w-full">
                    <span>{{ $btn['label'] }}</span>
                    <span class="badge inline-flex items-center justify-center w-5 h-5 rounded-full text-sm ml-1
                                 {{ $loop->first ? 'bg-white text-[#1F3B67]' : 'bg-gray-100 text-gray-500' }}">
                        {{ $btn['count'] }}
                    </span>
                </span>
            </button>
        @endforeach
        <button id="clearFilters" disabled
            class="rounded-none py-2 px-3 text-lg shadow bg-[#1F3B67] text-white disabled:opacity-80 disabled:cursor-not-allowed">
            Clear Filters
        </button>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 py-4">

        {{-- Left: Modules Table --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-none border border-gray-200 p-3">
                <h2 class="text-xl font-semibold text-gray-900 mb-4" id="cardTitle">All Modules</h2>
                <div class="overflow-x-auto">

                    <table class="w-full text-lg text-left" id="completedTable">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="pb-3 pr-3 text-base font-semibold text-gray-700 uppercase tracking-wide">#</th>
                                <th class="pb-3 pr-3 text-base font-semibold text-gray-700 uppercase tracking-wide">Course</th>
                                <th class="pb-3 pr-3 text-base font-semibold text-gray-700 uppercase tracking-wide text-center">Required</th>
                                <th class="pb-3 pr-3 text-base font-semibold text-gray-700 uppercase tracking-wide">Score</th>
                                <th class="pb-3 pr-3 text-base font-semibold text-gray-700 uppercase tracking-wide">Status</th>
                                <th class="pb-3 pr-3 text-base font-semibold text-gray-700 uppercase tracking-wide">Hours</th>
                            </tr>
                        </thead>
                        <tbody id="courseTableBody" class="divide-y divide-gray-100">
                            @foreach ($completions as $i => $completion)
                                <tr class="course-row hover:bg-gray-50 transition-colors"
                                    data-status="{{ $completion->completion_status }}"
                                    data-mandatory="{{ $completion->is_mandatory ? 'true' : 'false' }}">
                                    <td class="py-3 pr-3 text-gray-700">{{ $i + 1 }}</td>
                                    <td class="py-3 pr-3 text-gray-800 font-medium">{{ $completion->course_title_value  }}</td>
                                    <td class="py-3 pr-3 text-center">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-base
                                                     {{ $completion->courseRelationship->is_mandatory ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $completion->courseRelationship->is_mandatory ? '✓' : '✗' }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-3 text-gray-700">{{ $completion->formatted_score }}</td>
                                    <td class="py-3 pr-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-base font-medium
                                                     {{ $completion->completion_status === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $completion->completion_status }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-3 text-gray-600">{{ $completion->effort_hours }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div id="noResults" class="hidden py-10 text-center text-lg text-gray-700">
                        No modules match the selected filter.
                    </div>

                </div>
            </div>
        </div>

        {{-- Right: Stats --}}
        <div class="flex flex-col gap-4">
            <div class="bg-white rounded-none border border-gray-200 p-3">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_hours'] }}h</div>
                <div class="text-base text-gray-700 mt-1">Total Duration in Hours</div>
                <div class="flex justify-between text-base text-gray-500 mt-4 mb-1">
                    <span>Pass-to-attempt ratio</span>
                    <span class="font-semibold text-gray-700">{{ $stats['pass_ratio'] }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-[#1F3B67] h-2 rounded-full" style="width: {{ $stats['pass_ratio'] }}%"></div>
                </div>
                <div class="space-y-3 mt-2">
                    @foreach ([
                        ['Attempted Modules',      $stats['all']],
                        ['Completed',               $stats['completed']],
                        ['Failed',                  $stats['failed']],
                        // ['Mandatory Completed',     $stats['mandatory_completed']],
                        // ['Mandatory Modules Count', $stats['mandatory_all']],
                    ] as $item)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <span class="text-lg text-gray-600">{{ $item[0] }}</span>
                            <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-0.5 rounded-full text-base font-semibold bg-gray-100 text-black">
                                {{ $item[1] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const clearButton = document.getElementById('clearFilters');
        const tableBody = document.getElementById('courseTableBody');
        const noResults = document.getElementById('noResults');
        const courseRows = document.querySelectorAll('.course-row');
        const cardTitle = document.getElementById('cardTitle');

        const filterTitles = {
            'all':                 'All Modules',
            'completed':           'Completed',
            'failed':              'Failed',
            'mandatory_completed': 'Mandatory Completed',
            'mandatory_all':       'My Compulsory Modules',
        };

        function setActiveButton(filter) {
            filterBtns.forEach(btn => {
                const isActive = btn.getAttribute('data-filter') === filter;
                const badge = btn.querySelector('.badge');
                btn.classList.toggle('bg-[#1F3B67]', isActive);
                btn.classList.toggle('bg-[#1F3B67]/60', !isActive);
                if (badge) {
                    badge.classList.toggle('bg-white', isActive);
                    badge.classList.toggle('text-[#1F3B67]', isActive);
                    badge.classList.toggle('bg-gray-100', !isActive);
                    badge.classList.toggle('text-gray-500', !isActive);
                }
            });
        }

        function filterCourses(filter) {
            cardTitle.textContent = filterTitles[filter] || 'Modules';
            noResults.classList.add('hidden');

            let visibleCount = 0;
            courseRows.forEach(row => {
                const status = row.getAttribute('data-status');
                const isMandatory = row.getAttribute('data-mandatory') === 'true';
                let shouldShow = false;

                switch (filter) {
                    case 'all':                 shouldShow = true; break;
                    case 'completed':           shouldShow = status === 'Completed'; break;
                    case 'failed':              shouldShow = status !== 'Completed'; break;
                    case 'mandatory_completed': shouldShow = isMandatory && status === 'Completed'; break;
                    case 'mandatory_all':       shouldShow = isMandatory; break;
                }

                row.classList.toggle('hidden', !shouldShow);
                if (shouldShow) visibleCount++;
            });

            if (visibleCount === 0) {
                tableBody.classList.add('hidden');
                noResults.classList.remove('hidden');
            } else {
                tableBody.classList.remove('hidden');
                let i = 1;
                courseRows.forEach(row => {
                    if (!row.classList.contains('hidden')) {
                        row.querySelector('td:first-child').textContent = i++;
                    }
                });
            }
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');
                setActiveButton(filter);
                filterCourses(filter);
                clearButton.disabled = filter === 'all';
            });
        });

        clearButton.addEventListener('click', function () {
            setActiveButton('all');
            filterCourses('all');
            this.disabled = true;
        });

        setActiveButton('all');
        filterCourses('all');
    });
</script>

@endsection