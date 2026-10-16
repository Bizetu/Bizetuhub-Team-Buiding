<?php

namespace App\Http\Controllers\Front;

use App\Services\GoogleCalendarService;
use App\Repositories\PageRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CalendarEvent;
use App\Models\DueDate;
use App\Models\JobRole;
use Carbon\Carbon;

class DueDateController extends Controller
{
    private GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index()
    {
        $itemPage = app(PageRepository::class)->getPage('calendar');

        if (! $itemPage) {
            abort(404);
        }

        $user      = Auth::user();
        $search    = request()->query('search');
        $category  = request()->query('category');

        $weekly = $this->dueDateQuery(
            [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            $search,
            $category
        )->paginate(5, ['*'], 'weekly_page');

        $monthly = $this->dueDateQuery(
            [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            $search,
            $category
        )->paginate(5, ['*'], 'monthly_page');

        $quarterly = $this->dueDateQuery(
            [Carbon::now(), Carbon::now()->addMonths(3)],
            $search,
            $category
        )->paginate(5, ['*'], 'quarterly_page');

        // Get all calendar events for this user
        $calendarEvents = CalendarEvent::where('user_id', $user->id)
            ->pluck('event_id', 'course_id')
            ->toArray();

        // Check if Google Calendar is connected
        $isCalendarConnected = $this->calendarService->isConnected($user->id);

        // Attach calendar data to every row in every collection
        foreach ([$weekly, $monthly, $quarterly] as $paginator) {
            $paginator->getCollection()->transform(function ($dueDate) use ($calendarEvents, $isCalendarConnected) {
                // Legacy Google Calendar URL (fallback)
                $dueDate->calendar_url = $this->googleCalendarUrl(
                    $dueDate->course_name,
                    $dueDate->due_date,
                    $dueDate->short_name
                        ? route('courses.detail', $dueDate->short_name)
                        : null
                );

                // Check if this course already has a calendar event
                $dueDate->has_calendar_event = isset($calendarEvents[$dueDate->course_id]);
                $dueDate->calendar_event_id = $calendarEvents[$dueDate->course_id] ?? null;

                return $dueDate;
            });
        }

        return view('site.pages.calendar', [
            'pageItem'   => $itemPage,
            'configLms'  => config('settings.lms.live'),
            'jobRoles'   => JobRole::all(),
            'userObject' => $user,
            'search'     => $search,
            'category'   => $category,
            'weekly'     => $weekly,
            'monthly'    => $monthly,
            'quarterly'  => $quarterly,
            'isCalendarConnected' => $isCalendarConnected,
        ]);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Build a reusable due-date query for the authenticated user.
     *
     * Joins completion_reports and user_licenses so each row carries the
     * current user's completion / enrolment status without N+1 queries.
     *
     * @param  array{0: \Carbon\Carbon, 1: \Carbon\Carbon}  $range
     */
    private function dueDateQuery(array $range, ?string $search, ?string $category)
    {
        $user = Auth::user();
        $dueDates = DueDate::query()
            // Core course data
            ->join('courses', 'due_dates.course_id', '=', 'courses.id')
            // Per-user completion status (left join → NULL when not completed)
            ->leftJoin('completion_reports', function ($join) use ($user) {
                $join->on('due_dates.course_id', '=', 'completion_reports.course_id')
                     ->where('completion_reports.user_id', $user->id)
                     ->whereNull('completion_reports.deleted_at');
            })
            // Per-user enrolment status (left join → NULL when not enrolled)
            ->leftJoin('user_licenses', function ($join) use ($user) {
                $join->on('due_dates.course_id', '=', 'user_licenses.course_id')
                     ->where('user_licenses.user_id', $user->id)
                     ->whereNull('user_licenses.deleted_at');
            })
            ->leftJoin('user_evaluations', function ($join) use ($user) {
                $join->on('due_dates.course_id', '=', 'user_evaluations.course_id')
                    ->where('user_evaluations.user_id', $user->id);
            })
            ->select(
                'due_dates.id',
                'due_dates.course_id',
                'due_dates.due_date',
                'courses.display_name as course_name',
                'courses.short_name',
                'completion_reports.completion_date',
                'completion_reports.score',
                'completion_reports.completion_status',
                DB::raw('CASE WHEN user_evaluations.id IS NULL THEN 0 ELSE 1 END as evaluation_status'),
                'user_licenses.created_at as enrollment_date'
            )
            ->forUser($user)
            ->whereNull('due_dates.deleted_at')
            ->whereBetween('due_dates.due_date', $range)
            ->when($search, fn ($q) => $q->where('courses.display_name', 'LIKE', "%{$search}%"))
            ->when($category, fn ($q) => $q->where('due_dates.job_role_id', $category))
            ->distinct()
            ->orderBy('due_dates.due_date');

        return $dueDates;
    }

    /**
     * Build a "Add to Google Calendar" URL for an all-day event on the due date.
     * This is a fallback method when API integration is not available.
     */
    private function googleCalendarUrl(string $courseName, mixed $dueDate, ?string $courseUrl = null): string
    {
        $day  = Carbon::parse($dueDate)->format('Ymd');
        $next = Carbon::parse($dueDate)->addDay()->format('Ymd');

        return 'https://calendar.google.com/calendar/render?' . http_build_query([
            'action'  => 'TEMPLATE',
            'text'    => "Due: {$courseName}",
            'dates'   => "{$day}/{$next}",
            'details' => $courseUrl
                ? "Complete this course by the due date.\n\nCourse link: {$courseUrl}"
                : 'Complete this course by the due date on your eLearning platform.',
        ]);
    }

    public function icalendar()
    {
        $key = 'icalendar';

        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.icalendar', [
            'pageItem' => $itemPage,
        ]);
    }
}
