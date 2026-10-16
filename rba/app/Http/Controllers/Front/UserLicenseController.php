<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLicense;
use App\Models\Course;
use App\Models\CompletionReport;
use App\Repositories\PageRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Partner;
use App\Models\SerialNumber;
use Carbon\Carbon;
use App\Edx\EdxAuthUser;
use App\Edx\StudentCourseEnrollment;
use App\Edx\StudentCourseCompletion;
use App\Edx\StudentCourseware;
use App\Events\CourseProgressSync;
use Illuminate\Support\Facades\Event;
use App\Repositories\SerialNumberRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserLicenseController extends Controller
{
    //
    protected $serialNumberRepository;

    const COMPLETION_STATUSES = ['completed' =>'Completed', 'failed' => 'Failed', 'in_progress'=>'In Progress'];
    const MANDATORY_STATUSES = ['1' =>'Mandatory', '2' => 'Not Mandatory'];

    public function __construct(SerialNumberRepository $serialNumberRepository)
    {
        $this->serialNumberRepository = $serialNumberRepository;
    }


    public function courses(Request $request)
    {
        return  App::environment(['local', 'staging']) ? $this->coursesLocal($request) : $this->coursesLive($request);
    }

    public function coursesLocal(Request $request)
    {
        $key = 'profile-courses';
        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        $completionStatuses = self::COMPLETION_STATUSES;
        $mandatoryStatuses = self::MANDATORY_STATUSES;

        $licenses = Auth::user()->licenses()->with(['course', 'completion'])->where('published', 1)->get();

        // Now $licenses is available
        event(new CourseProgressSync(
            Auth::user()->email,
            $licenses->pluck('course.course_id')->toArray()
        ));

        $licenses = Auth::user()->licenses()
            ->with(['course', 'completion'])
            ->where('published', 1)
            ->when(request('course_name'), function ($query, $course_name) {
                $query->whereHas('course', function ($q) use ($course_name) {
                    $q->where('display_name', 'like', '%' . $course_name . '%');
                });
            })
            ->when(request('mandatory_status'), function ($query, $mandatory_status) {
                if ($mandatory_status == 1) {
                    $query->whereHas('course'); // all courses are mandatory, so just ensure course exists
                } else {
                    $query->whereHas('course', fn($q) => $q->whereRaw('1 = 0')); // nothing is non-mandatory yet
                }
            })
            ->when(request('status'), function ($query, $status) {
                if ($status === 'completed') {
                    $query->whereHas('completion', function ($q) {
                        $q->where('score', '>', 0.79);
                    });
                } elseif ($status === 'failed') {
                    $query->whereHas('completion', function ($q) {
                        $q->whereBetween('score', [0.2, 0.79]);
                    });
                } elseif ($status === 'in_progress') {
                    $query->where(function ($q) {
                        $q->doesntHave('completion')
                            ->orWhereHas('completion', function ($q) {
                                $q->where('score', '<', 0.2);
                            });
                    });
                }
            })
            ->orderByRaw('
                CASE WHEN (
                    SELECT MAX(completion_date) 
                    FROM completion_reports
                    WHERE completion_reports.user_id = user_licenses.user_id
                    AND completion_reports.course_id = user_licenses.course_id
                ) IS NOT NULL THEN 0 ELSE 1 END ASC
            ')
            ->orderByRaw('
                (SELECT MAX(completion_date) 
                FROM completion_reports
                WHERE completion_reports.user_id = user_licenses.user_id
                AND completion_reports.course_id = user_licenses.course_id) DESC
            ')
            ->orderBy('user_licenses.created_at', 'DESC')
            ->get();


        return view('site.pages.profile-courses', [
            'user'          => $request->user(),
            'pageItem'      => $itemPage,
            'licenses' => $licenses,
            'completionStatuses' => $completionStatuses,
            'mandatoryStatuses' => $mandatoryStatuses
        ]);
    }

    public function coursesLive(Request $request)
    {
        $key = 'profile-courses';
        $itemPage = app(PageRepository::class)->getPage($key);

        if (!$itemPage) {
            abort(404);
        }

        $completionStatuses = self::COMPLETION_STATUSES;
        $mandatoryStatuses = self::MANDATORY_STATUSES;

        $licenses = Auth::user()->licenses()
            ->with(['course', 'completion'])
            ->where('published', 1)
            ->get();
        foreach ($licenses as $license) {
            $this->getSingleProgress(Auth::user()->email, $license->course->course_id);
        }

        // Now $licenses is available
        event(new CourseProgressSync(
            Auth::user()->email,
            $licenses->pluck('course.course_id')->toArray()
        ));

        $licenses = Auth::user()->licenses()
            ->with(['course', 'completion'])
            ->where('published', 1)
            ->when(request('course_name'), function ($query, $course_name) {
                $query->whereHas('course', function ($q) use ($course_name) {
                    $q->where('display_name', 'like', '%' . $course_name . '%');
                });
            })
            ->when(request('mandatory_status'), function ($query, $mandatory_status) {
                if ($mandatory_status == 1) {
                    $query->whereHas('course'); // all courses are mandatory, so just ensure course exists
                } else {
                    $query->whereHas('course', fn($q) => $q->whereRaw('1 = 0')); // nothing is non-mandatory yet
                }
            })
            ->when(request('status'), function ($query, $status) {
                if ($status === 'completed') {
                    $query->whereHas('completion', function ($q) {
                        $q->where('score', '>', 0.79);
                    });
                } elseif ($status === 'failed') {
                    $query->whereHas('completion', function ($q) {
                        $q->whereBetween('score', [0.2, 0.79]);
                    });
                } elseif ($status === 'in_progress') {
                    $query->where(function ($q) {
                        $q->doesntHave('completion')
                            ->orWhereHas('completion', function ($q) {
                                $q->where('score', '<', 0.2);
                            });
                    });
                }
            })
            ->orderByRaw('
                CASE WHEN (
                    SELECT MAX(completion_date) 
                    FROM completion_reports
                    WHERE completion_reports.user_id = user_licenses.user_id
                    AND completion_reports.course_id = user_licenses.course_id
                ) IS NOT NULL THEN 0 ELSE 1 END ASC
            ')
            ->orderByRaw('
                (SELECT MAX(completion_date) 
                FROM completion_reports
                WHERE completion_reports.user_id = user_licenses.user_id
                AND completion_reports.course_id = user_licenses.course_id) DESC
            ')
            ->orderBy('user_licenses.created_at', 'DESC')
            ->get();

        return view('site.pages.profile-courses', [
            'user'          => $request->user(),
            'pageItem'      => $itemPage,
            'licenses' => $licenses,
            'completionStatuses' => $completionStatuses,
            'mandatoryStatuses' => $mandatoryStatuses
        ]);
    }

    public function progress(Request $request)
    {
        $key = 'profile-progress';

        $itemPage = app(PageRepository::class)->getPage($key);

        $userLicenseMap = Auth::user()->licenses()
            ->with('completion')
            ->get()
            ->keyBy('course_id');

        $activeCourseIds = $userLicenseMap->keys();

        $completions = Auth::user()->completions()
            ->whereIn('course_id', $activeCourseIds)
            ->with(['courseRelationship', 'userLicenses'])->get();

        $completionRate = $completions->count() > 0 ? round(($completions->where('completion_status', 'Completed')->count() / $completions->count()) * 100) : 0;
        $totalHours = $completions->where('score', '>', 0.2)->pluck('effort_hours')->toArray();

        $stats = [
            'all'                 => $completions->count(),
            'completed'           => $completions->where('completion_status', 'Completed')->count(),
            'failed'              => $completions->where('completion_status', 'Failed')->count(),
            'total_hours'         => array_sum($totalHours),
            'pass_ratio'          => $completionRate
        ];

        if (!$itemPage) {
            abort(404);
        }

        return view('site.pages.profile-progress', [
            'user' => $request->user(),
            'pageItem' => $itemPage,
            'stats' => $stats,
            'completions' => $completions,
        ]);
    }

    public function downloadProgress()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $records = Course::published()
            ->leftJoin('user_licenses', function ($join) {
                $join->on('user_licenses.course_id', '=', 'courses.id')
                    ->where('user_licenses.user_id', Auth::id());
            })
            ->leftJoin('completion_reports', function ($join) {
                $join->on('completion_reports.course_id', '=', 'courses.id')
                    ->where('completion_reports.user_id', Auth::id());
            })
            ->select([
                'courses.id',
                'courses.display_name as course',
                'user_licenses.id as license_id',
                'completion_reports.id as report_id',
                'completion_reports.completion_status as completion_status',
                'completion_reports.completion_date',
                'completion_reports.effort_hours',
                'completion_reports.score',
            ])
            ->orderBy('completion_date', 'desc')
            ->get();

        $completions = $records->whereNotNull('completion_date');
        $enrollments = $records->whereNotNull('license_id')
            ->whereNull('completion_date')
            ->sortBy([
                [fn($item) => is_null($item->completion_status) ? 1 : 0, 'asc'], // nulls last
                ['completion_status', 'desc'],
                ['license_id', 'asc'],
            ]);
        $yetStarted = $records->whereNull('license_id')->whereNull('completion_date')->sortBy('created_at');

        $combined = $completions
            ->merge($enrollments)
            ->merge($yetStarted)
            ->values();

        // $completions = CompletionReport::where('user_id', $user->id)->whereHas('courseRelationship')->get();
        // $licenses = UserLicense::where('user_id', $user->id)->whereHas('course')->with('course')->get();
        // $courses = Course::published()->get();

        // $mandatoryCourseIds = $mandatory->pluck('course_id')->toArray();

        // $completions = $completions->map(function($completion) use ($mandatoryCourseIds) {
        //     $completion->is_mandatory = in_array($completion->course_id, $mandatoryCourseIds);
        //     return $completion;
        // });

        $index = 1;
        $enrolled_index = 1;
        $index_mandatory = 1;

        $date = now()->format('l, jS F h:i_A');

        $logo = public_path('images/certificateAssets/rba-logo.png');
        $logo = base64_encode(file_get_contents($logo));
        $logo = 'data:image/png;base64,' . $logo;

        $report = [
            'user' => $user,
            'completions' => $completions,
            'mandatory' => $combined,
            'index' => $index,
            'enrolled_index' => $enrolled_index,
            'index_mandatory' => $index_mandatory,
            'date' => $date,
            'logo' => $logo,
            'jobrole' => $user->jobrole->title,
            'company' => $user->company->title,
            'effort_hours' => $records->pluck('effort_hours')->sum()
        ];

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
            'fontDir' => storage_path('fonts'),
            'fontCache' => storage_path('fonts'),
            'defaultFont' => 'Aptos',
        ])->loadView('site.pages.user_licenses.progress', compact('report'))
            ->setPaper('a4', 'landscape');

        $fileName = 'Progress_Snapshot_' . str_replace(' ', '_', $report['user']->name) . '_' . $date . '.pdf';

        return $pdf->download($fileName);
    }

    public function certificateOverall($userid)
    {
        $modules = Course::published()->pluck('display_name')->toArray();
        $completion = CompletionReport::where('user_id', Auth::id())->orderBy('id', 'desc')->first();
        $completionDate = Carbon::parse($completion->completion_date) ?? null;
        $user = Auth::user();

        $serial = $this->serialNumberRepository->findByUserAndCourse(
            $user->id,
            null,  // no course_id here
            1      // certificate type 1
        );

        if ($serial) {
            $cert_number = $serial->serial_number;
            $this->serialNumberRepository->updateSerialNumber($serial->id, [
                'certificate_type' => 1,
            ]);
        } else {
            $serial = $this->serialNumberRepository->createSerialNumber([
                'user_id'          => $user->id,
                'email'            => $user->email,
                'certificate_type' => 1,
                'serial_number'    => self::generateRandomString(16),
            ]);
            $cert_number = $serial->serial_number;
        }

        $columns = array_chunk($modules, ceil(count($modules) / 2));

        $partners = Partner::published()->orderBy('id', 'asc')->get();
        // Swap the last two
        $count = $partners->count();
        if ($count >= 2) {
            $last = $partners->get($count - 1);
            $secondLast = $partners->get($count - 2);

            $partners = $partners->map(function ($partner, $index) use ($count, $last, $secondLast) {
                if ($index === $count - 2) return $last;
                if ($index === $count - 1) return $secondLast;
                return $partner;
            });
        }

        $payload = [];
        $payload['jobrole'] =   $user->jobrole?->title ?? '';
        $payload['name'] = $user->name;
        $payload['date'] =   $completionDate->format('Y-m-d');
        $payload['serial'] = $cert_number;
        $payload['company'] = $user->company?->title ?? '';
        $payload['partners'] =  $partners;
        $payload['columns'] = $columns;

        // dd($payload);


        // dd($partners->count());

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
            'fontDir' => storage_path('fonts'),
            'fontCache' => storage_path('fonts'),
            'defaultFont' => 'Aptos',
        ])->loadView('site.pages.user_licenses.certificate', compact('payload'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Certificate of Completion.pdf');

        return $pdf->download('Certificate of Completion - ' . $payload['name'] . '.pdf');
    }

    public function certificateSingleModule($courseid)
    {
        $course = Course::published()->first();
        $completion = CompletionReport::where('user_id', Auth::id())->where('course_id', $courseid)->first();
        $completionDate = Carbon::parse($completion->completion_date) ?? null;
        $user = Auth::user();
        $number = $this->serialNumberRepository->findByUserAndCourse(
            $user->id,
            $course->course_id,
            2
        );

        if ($number) {
            $cert_number = $number->serial_number;
            $this->serialNumberRepository->updateSerialNumber($number->id, [
                'serial_number'    => self::generateRandomString(16),
                'course_id'        => $course->course_id,
                'certificate_type' => 2,
            ]);
        } else {
            $serial = $this->serialNumberRepository->createSerialNumber([
                'email'         =>$user->email,
                'user_id'          => $user->id,
                'course_id'        => $course->course_id,
                'certificate_type' => 2,
                'serial_number'    => self::generateRandomString(16),
            ]);
            $cert_number = $serial->serial_number;
        }

        $partners = Partner::published()->orderBy('id', 'asc')->get();
        // Swap the last two
        $count = $partners->count();
        if ($count >= 2) {
            $last = $partners->get($count - 1);
            $secondLast = $partners->get($count - 2);

            $partners = $partners->map(function ($partner, $index) use ($count, $last, $secondLast) {
                if ($index === $count - 2) return $last;
                if ($index === $count - 1) return $secondLast;
                return $partner;
            });
        }

        $payload = [];
        $payload['jobrole'] =   $user->jobrole?->title ?? '';
        $payload['name'] = ($user->first_name . ' ' . $user->last_name) ?? '';
        $payload['date'] =   $completionDate->format('Y-m-d');
        $payload['course'] = $course->display_name ?? '';
        $payload['serial'] = $cert_number;
        $payload['company'] = $user->company?->title ?? '';
        $payload['partners'] =  $partners;


        // dd($payload);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
            'fontDir' => storage_path('fonts'),
            'fontCache' => storage_path('fonts'),
            'defaultFont' => 'Aptos',
        ])->loadView('site.pages.user_licenses.module_certificate', compact('payload'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('Module Completion Badge - ' . $payload['name'] . '.pdf');
    }

    public function adminCertificateDownload($userId)
    {

        $userData = request()->session()->get('userData');

        if(!$userData){
        throw new AuthorizationException('You are not allowed on this page!');
        }
        
        $modules = Course::published()->pluck('display_name')->toArray();
        $completion = CompletionReport::where('user_id', Auth::id())->orderBy('id', 'desc')->first();
        $completionDate = Carbon::parse($completion->completion_date) ?? null;
        $user = Auth::user();

        $serial = $this->serialNumberRepository->findByUserAndCourse(
            $user->id,
            null,  // no course_id here
            1      // certificate type 1
        );

        if ($serial) {
            $cert_number = $serial->serial_number;
            $this->serialNumberRepository->updateSerialNumber($serial->id, [
                'certificate_type' => 1,
            ]);
        } else {
            $serial = $this->serialNumberRepository->createSerialNumber([
                'user_id'          => $user->id,
                'email'            => $user->email,
                'certificate_type' => 1,
                'serial_number'    => self::generateRandomString(16),
            ]);
            $cert_number = $serial->serial_number;
        }

        $columns = array_chunk($modules, ceil(count($modules) / 2));

        $partners = Partner::published()->orderBy('id', 'asc')->get();
        // Swap the last two
        $count = $partners->count();
        if ($count >= 2) {
            $last = $partners->get($count - 1);
            $secondLast = $partners->get($count - 2);

            $partners = $partners->map(function ($partner, $index) use ($count, $last, $secondLast) {
                if ($index === $count - 2) return $last;
                if ($index === $count - 1) return $secondLast;
                return $partner;
            });
        }

        $payload = [];
        $payload['jobrole'] =   $user->jobrole?->title ?? '';
        $payload['name'] = $user->name;
        $payload['date'] =   $completionDate->format('Y-m-d');
        $payload['serial'] = $cert_number;
        $payload['company'] = $user->company?->title ?? '';
        $payload['partners'] =  $partners;
        $payload['columns'] = $columns;

        // dd($payload);


        // dd($partners->count());

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'chroot' => public_path(),
            'fontDir' => storage_path('fonts'),
            'fontCache' => storage_path('fonts'),
            'defaultFont' => 'Aptos',
        ])->loadView('site.pages.user_licenses.certificate', compact('payload'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Certificate of Completion.pdf');

        return $pdf->download('Certificate of Completion - ' . $payload['name'] . '.pdf');

    }

    public function trainingFeed(Request $request)
    {
        $completionStatuses = self::COMPLETION_STATUSES;
        $completionStatuses = self::COMPLETION_STATUSES;
        $licenses = Auth::user()->licenses()
            ->with(['course', 'completion'])
            ->where('published', 1)
            ->when(request('course_name'), function ($query, $course_name) {
                $query->whereHas('course', function ($q) use ($course_name) {
                    $q->where('display_name', 'like', '%' . $course_name . '%');
                });
            })
            ->when(request('status'), function ($query, $status) {
                if ($status === 'completed') {
                    $query->whereHas('completion', function ($q) {
                        $q->where('score', '>', 0.79);
                    });
                } elseif ($status === 'failed') {
                    $query->whereHas('completion', function ($q) {
                        $q->whereBetween('score', [0.2, 0.79]);
                    });
                } elseif ($status === 'in_progress') {
                    $query->where(function ($q) {
                        $q->doesntHave('completion')
                            ->orWhereHas('completion', function ($q) {
                                $q->where('score', '<', 0.2);
                            });
                    });
                }
            })
            ->orderByRaw('COALESCE(
                (SELECT MAX(completion_date) FROM completion_reports
                WHERE completion_reports.user_id = user_licenses.user_id
                AND completion_reports.course_id = user_licenses.course_id),
                user_licenses.created_at
            ) DESC')
            ->get();

        return view('site.pages.training-feed', [
            'user'          => $request->user(),
            'licenses' => $licenses,
            'completionStatuses' => $completionStatuses
        ]);
    }


       public function processProfile(Request $request)
    {
        if ($request->isMethod('post')) {
            $id = str_replace(' ', '+', $request->id);
            $user = User::where('username', $request->username)->first();

            $sync =  $this->getSingleProgress($user->email, $id);

            if ($sync) {
                return response()->json(['message' => $sync, 'status' => "OK"], 201);
            } else {
                return response()->json(['message' => 'Sync not done', 'status' => "error"], 201);
            }
            // $this->syncSpecials($id, $user);

        }
    }


    private function getSingleProgress($email, $courseId)
    {
        if ($courseId) {

            $grades = array();
            $max_grades = array();
            $user = EdxAuthUser::where('email', $email)->firstOrFail();
            $micrositeUser = User::where('email', $email)->first();
            $course = Course::where('course_id', $courseId)->first();
            $license = UserLicense::where('user_id', $micrositeUser->id)->where('course_id', $course->id)->first();
            $effort = $license->course ? $license->course->effort : '0';

            if ($effort && strpos($effort, ':') !== false) {
                list($days, $hours, $minutes) = array_map('intval', explode(':', $effort));
                $effort = (($days * 24) + ($hours) + ($minutes / 60));
            } else {
                $effort = 0;
            }

            if ($user) {
                $enrollment = StudentCourseEnrollment::where(['user_id' => $user->id, 'course_id' => $courseId])->first();
            } else {
                $enrollment = null;
            }

            if (!$course->gradeable) {
                CompletionReport::UpdateOrCreate(
                    [
                        'course_id' => $course->id,
                        'user_id' => $micrositeUser->id,
                        'user_license_id' => $license->id
                    ],
                    [
                        'email' => $micrositeUser->email,
                        'username' => $micrositeUser->username,
                        'company_id' => $micrositeUser->company_id,
                        'job_role_id' => $micrositeUser->job_role_id,
                        'branch_id' => $micrositeUser->branch_id,
                        'department_id' => $micrositeUser->department_id,
                        'score' => 1,
                        'max_score' => 1,
                        // 'completion_status' => $letter_grade,
                        // 'completion_date' => ($total > 0) ? date('Y-m-d H:i:s', strtotime($completion)) : '',
                        // 'enrollment_date' => (!empty($enrollment->created)) ? $enrollment->created : $license->created_at,
                        'completion_status' => 'Completed',
                        'completion_date' => now()->format('Y-m-d H:i:s'),
                        'enrollment_date' => (!empty($enrollment) && !empty($enrollment->created)) ? $enrollment->created : $license->created_at,
                        'gender_id' => $micrositeUser->department_id,
                        'user_license_id' => $license->id,
                        'effort_hours' => $effort,
                        'published' => 1
                    ]
                );
            } else {

                if ($user) {

                    $enrollment = StudentCourseEnrollment::where(['user_id' => $user->id, 'course_id' => $courseId])->first();
                    $completion = StudentCourseCompletion::where(['student_id' => $user->id, 'module_type' => 'problem', 'course_id' => $courseId])->orderBy('id', 'DESC')->first();

                    if ($enrollment) {

                        $datas = StudentCourseware::where('course_id', $courseId)->where('student_id', $user->id)->where('module_type', '=', 'problem')->get();

                        if ($datas) {
                            foreach ($datas as $data) {
                                $grades[] = $data->grade;
                                $max_grades[] = $data->max_grade;
                            }
                        }

                        if (array_sum($max_grades) == 0) {
                            $max_grades = [0 => 1];
                        }

                        if (array_sum($grades) != 0) {

                            $total = (array_sum($grades) / array_sum($max_grades));

                            if ($total >= 0.80) {

                                $letter_grade = 'Completed';
                                $completion = date('Y-m-d H:i:s', strtotime($completion->modified));
                            } elseif ($total <= 0.79 && $total >= 0.20) {

                                $letter_grade = 'Failed';
                                $completion = date('Y-m-d H:i:s', strtotime($completion->modified));
                            } else {
                                $letter_grade = 'In Progress';
                                $completion = '';
                            }

                            CompletionReport::UpdateOrCreate(
                                [
                                    'course_id' => $course->id,
                                    'user_id' => $micrositeUser->id,
                                    'user_license_id' => $license->id
                                ],
                                [
                                    'email' => $micrositeUser->email,
                                    'username' => $micrositeUser->username,
                                    'company_id' => $micrositeUser->company_id,
                                    'job_role_id' => $micrositeUser->job_role_id,
                                    'branch_id' => $micrositeUser->branch_id,
                                    'department_id' => $micrositeUser->department_id,
                                    'score' => (float)$total,
                                    'max_score' => 1,
                                    'completion_status' => $letter_grade,
                                    'completion_date' => ($total > 0) ? date('Y-m-d H:i:s', strtotime($completion)) : '',
                                    'enrollment_date' => (!empty($enrollment->created)) ? $enrollment->created : $license->created_at,
                                    'gender_id' => $micrositeUser->department_id,
                                    'user_license_id' => $license->id,
                                    'effort_hours' => $effort,
                                    'published' => 1
                                ]
                            );
                        }
                    }

                    return true;
                } else {

                    return false;
                }
            }
        }
    }





    protected function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
