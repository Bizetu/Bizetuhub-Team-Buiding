@extends('layouts.app')

@section('title', 'Training Calendar - Rwanda Bankers Association')

@section('content')

    <x-hero
        title="{{ $pageItem->header_title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Training Calendar', 'url' => 'calendar'],
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <x-calendar-nav-tabs active="calendar" />

    <div class="container my-4">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('calendar') }}" class="w-full lg:w-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search courses..."
                        class="w-full lg:w-80 px-4 py-2.5 pr-12 text-[#1f3b67] bg-gray-200 rounded-full ring-1 ring-[#1f3b67]/40 focus:outline-none focus:ring-2 focus:ring-[#1f3b67] focus:bg-white transition-all text-base">

                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-[#1f3b67] hover:bg-[#ffb901] rounded-full transition-colors group">
                        <svg class="w-4 h-4 text-white group-hover:text-[#1f3b67] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                @error('search')
                    <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                @enderror

                @if($search)
                    <div class="mt-2 text-base">
                        <span class="text-[#1f3b67]/90">
                            Results for: <strong>{{ $search }}</strong>
                        </span>
                        <a href="{{ route('calendar') }}" class="ml-2 text-[#1f3b67] hover:text-[#ffb901] font-medium">
                            Clear
                        </a>
                    </div>
                @endif
            </form>

            @if (Auth::user()->is_google_user)
                @if($isCalendarConnected)
                    <div class="w-full lg:w-auto flex-shrink-0 px-4 py-2.5 bg-[#1f3b67]/5 border border-[#1f3b67]/20 rounded-full flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#1f3b67]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-base font-medium text-[#1f3b67] whitespace-nowrap">
                                Google Calendar connected
                            </span>
                        </div>
                        <form method="POST" action="{{ route('google.calendar.disconnect') }}">
                            @csrf
                            <button type="submit" class="text-base text-[#1f3b67] hover:text-black font-medium whitespace-nowrap">
                                Disconnect
                            </button>
                        </form>
                    </div>
                @else
                    <div class="w-full lg:w-auto flex-shrink-0">
                        <a href="{{ route('google.calendar.redirect') }}" class="inline-flex items-center justify-center w-full lg:w-auto px-4 py-2.5 bg-[#1f3b67]/90 text-white font-medium rounded-full hover:bg-[#1f3b67] transition-colors gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
                            </svg>
                            Connect Google Calendar
                        </a>
                    </div>
                @endif
            @endif
        </div>

        <div id="due-date-accordion" class="space-y-3">

            @foreach ([
                ['courses' => $weekly,    'label' => 'Courses due this week',    'id' => 'weekly', 'unavailable' => 'No courses due this week'],
                ['courses' => $monthly,   'label' => 'Courses due this month',   'id' => 'monthly', 'unavailable' => 'No courses due this month'],
                ['courses' => $quarterly, 'label' => 'Courses due this quarter', 'id' => 'quarterly', 'unavailable' => 'No courses due this quarter'],
            ] as $section)

                @php $open = $loop->first; @endphp

                <div class="rounded-xl border border-gray-200 shadow-sm overflow-hidden bg-white">
                    <button type="button" data-target="panel-{{ $section['id'] }}" aria-expanded="{{ $open ? 'true' : 'false' }}"
                        class="accordion-trigger w-full flex items-center justify-between p-4 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-black text-xl text-[#1f3b67]">
                            {{ $section['label'] }}
                        </span>

                        <svg class="w-5 h-5 transition-transform duration-300 {{ $open ? 'rotate-180' : '' }}" data-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="panel-{{ $section['id'] }}" class="{{ $open ? '' : 'hidden' }}">
                        <div class="border-t border-gray-100">
                            <div class="overflow-x-auto">
                                <table class="w-full text-base text-left">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold text-[#1f3b67]">Course</th>
                                            <th class="px-4 py-3 font-semibold text-[#1f3b67]">Due Date</th>
                                            <th class="px-4 py-3 font-semibold text-[#1f3b67] text-center">Status</th>
                                            <th class="px-4 py-3 font-semibold text-[#1f3b67] text-center">Certificate</th>
                                            <th class="px-4 py-3 font-semibold text-[#1f3b67] text-center">Calendar</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($section['courses'] as $course)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-medium">
                                                    @if ($course->short_name)
                                                        <a href="{{ route('courses.detail', $course->short_name) }}" target="_blank">
                                                            {{ $course->course_name }}
                                                        </a>
                                                    @else
                                                        {{ $course->course_name }}
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $course->due_date ? \Carbon\Carbon::parse($course->due_date)->isoFormat('MMMM Do YYYY') : '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if (!empty($course->completion_date))
                                                        @if ($course->score >= 0.7)
                                                            <span class="text-base border-1 border-[#1F3B67] p-2 px-3">
                                                                Completed
                                                            </span>
                                                        @else
                                                            <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $course->course_id . '/courseware' }}" target="_blank"
                                                                class="text-base border-1 border-[#1F3B67] p-2 px-3">
                                                                Retake Course
                                                            </a>
                                                        @endif
                                                    @elseif (!empty($course->enrollment_date))
                                                        <a href="{{ $configLms['LMS_BASE'] . '/courses/' . $course->course_id . '/courseware' }}" target="_blank"
                                                            class="text-base border-1 border-[#1F3B67] p-2 px-3"
                                                        >
                                                            In Progress
                                                        </a>
                                                    @else
                                                        <a href="{{ route('courses.enroll', $course->course_id) }}" target="_blank"
                                                            class="text-base border-1 border-[#1F3B67] p-2 px-3"
                                                        >
                                                            Enroll to Start
                                                        </a>
                                                    @endif
                                                </td>

                                                <td class="px-4 py-3 text-center">
                                                    @if (!empty($course->completion_date) && $course->score >= 0.6)
                                                        @if($course->evaluation_status)
                                                            <a href="{{ route('profile.certificateSingleModule', $course->course_id) }}" target="_blank"
                                                                class="text-base font-medium text-[#1F3B67] p-2 px-3 w-full flex justify-center bg-[#FFB901] cursor-pointer hover:bg-[#1F3B67] hover:text-white"
                                                            >
                                                                Download
                                                            </a>
                                                        @else
                                                            <a href="{{route('course.evaluate', $course->course_id) }}" class="text-base font-medium text-[#1F3B67] p-2 px-3 flex justify-center bg-[#FFB901] cursor-pointer hover:bg-[#1F3B67] hover:text-white">
                                                                Evaluate and Download Badge
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="text-base font-medium text-[#1F3B67] p-2 px-3  w-full flex justify-center border-1 border-[#1F3B67] cursor-not-allowed">
                                                            Not Certified
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="px-4 py-3 text-center">
                                                    <div class="flex flex-col sm:flex-row gap-2 justify-center items-center">
                                                        @if (Auth::user()->is_google_user)
                                                            @if($course->has_calendar_event)
                                                                <a href="https://calendar.google.com/calendar/u/0/r"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="inline-flex items-center justify-center gap-1 text-sm font-medium text-white bg-[#1F3B67] px-3 py-2 hover:opacity-90 transition w-full sm:w-auto">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 4h6a2 2 0 002-2V8a2 2 0 00-2-2H9a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    <span>View</span>
                                                                </a>
                                                            @endif
                                                            @if($isCalendarConnected)
                                                                @if($course->has_calendar_event)
                                                                    <button
                                                                        class="inline-flex items-center justify-center gap-1 text-sm font-medium text-[#1F3B67] border border-[#1F3B67] px-3 py-2 hover:bg-gray-50 transition w-full sm:w-auto remove-from-calendar"
                                                                        data-event-id="{{ $course->calendar_event_id }}"
                                                                        data-course-id="{{ $course->course_id }}"
                                                                        title="Remove from Google Calendar">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor" stroke-width="2">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                d="M5 13l4 4L19 7"/>
                                                                        </svg>
                                                                        <span>Added</span>
                                                                    </button>
                                                                @else
                                                                    <button
                                                                        class="inline-flex items-center justify-center gap-1 text-sm font-medium text-[#1F3B67] border border-[#1F3B67] px-3 py-2 hover:bg-gray-50 transition w-full sm:w-auto add-to-calendar"
                                                                        data-course-id="{{ $course->course_id }}"
                                                                        data-course-name="{{ $course->course_name }}"
                                                                        data-due-date="{{ $course->due_date }}"
                                                                        data-course-url="{{ $course->short_name ? route('courses.detail', $course->short_name) : '' }}"
                                                                        title="Add to Google Calendar">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor" stroke-width="2">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                        </svg>
                                                                        <span>Add</span>
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <a href="{{ $course->calendar_url }}"
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    class="inline-flex items-center justify-center gap-1 text-sm font-medium text-[#1F3B67] border border-[#1F3B67] px-3 py-2 hover:bg-gray-50 transition w-full sm:w-auto"
                                                                    title="Add to Google Calendar">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor" stroke-width="2">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    <span>Add</span>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="{{ $course->calendar_url }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="inline-flex items-center justify-center gap-1 text-sm font-medium text-[#1F3B67] border border-[#1F3B67] px-3 py-2 hover:bg-gray-50 transition w-full sm:w-auto"
                                                                title="Add to Google Calendar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <span>Add</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-8 text-center italic text-[#1f3b67]">
                                                    {{ $section['unavailable'] }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($section['courses']->hasPages())
                                <div class="px-4 py-3 border-t border-gray-100">
                                    {{ $section['courses']->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Accordion functionality
            const triggers = document.querySelectorAll('.accordion-trigger');
            triggers.forEach(trigger => {
                trigger.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const panel = document.getElementById(targetId);
                    const icon = this.querySelector('[data-icon]');
                    const isOpen = !panel.classList.contains('hidden');
                    if (isOpen) {
                        panel.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        panel.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                        this.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Add to calendar functionality
            const addButtons = document.querySelectorAll('.add-to-calendar');
            addButtons.forEach(button => {
                button.addEventListener('click', async function () {
                    const courseId = this.dataset.courseId;
                    const courseName = this.dataset.courseName;
                    const dueDate = this.dataset.dueDate;
                    const courseUrl = this.dataset.courseUrl;

                    // Disable button and show loading state
                    this.disabled = true;
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Adding...';

                    try {
                        const response = await fetch('{{ route("api.calendar.add") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                course_id: courseId,
                                course_name: courseName,
                                due_date: dueDate,
                                course_url: courseUrl
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Change button to "Added" state
                            this.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-3.5 h-3.5 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Added
                            `;
                            this.classList.remove('btn_border_primary', 'add-to-calendar');
                            this.classList.add('btn_border_secondary', 'remove-from-calendar');
                            this.dataset.eventId = data.event_id || '';

                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'success',
                                    title: 'Event added to Google Calendar successfully!'
                                }
                            }));
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            throw new Error(data.error || 'Failed to add event');
                        }
                    } catch (error) {
                        console.error('Error adding to calendar:', error);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                title: error.message || 'Failed to add event to calendar'
                            }
                        }));
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                });
            });

            // Remove from calendar functionality
            document.addEventListener('click', async function(e) {
                if (e.target.closest('.remove-from-calendar')) {
                    const button = e.target.closest('.remove-from-calendar');
                    const eventId = button.dataset.eventId;
                    const courseId = button.dataset.courseId;

                    if (!confirm('Remove this event from your Google Calendar?')) {
                        return;
                    }

                    button.disabled = true;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Removing...';

                    try {
                        const response = await fetch('{{ route("api.calendar.remove") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                event_id: eventId
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Change button back to "Add" state
                            button.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-3.5 h-3.5 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Add
                            `;
                            button.classList.remove('btn_border_secondary', 'remove-from-calendar');
                            button.classList.add('btn_border_primary', 'add-to-calendar');
                            delete button.dataset.eventId;
                            button.disabled = false;

                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'success',
                                    title: 'Event removed from Google Calendar'
                                }
                            }));
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            throw new Error(data.error || 'Failed to remove event');
                        }
                    } catch (error) {
                        console.error('Error removing from calendar:', error);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                title: error.message || 'Failed to remove event from calendar'
                            }
                        }));
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                }
            });
        });
    </script>
@endsection
