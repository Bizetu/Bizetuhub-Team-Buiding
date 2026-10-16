<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use App\Models\Course;
use Illuminate\Http\Request;
use Auth;
use Session;
use Carbon\Carbon;
use App\Models\UserLicense;
use App\Edx\EdxAuthUser;
use App\Edx\AccessToken;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    //
    public function courses(Request $request){
        $key = "courses";
        
        $itemPage =  app(PageRepository::class)->getPage($key);
        $coursesList = Course::published()->orderBy('display_name')->pluck('display_name','course_id');

        //Create a map of userlicenses to be used to get status for each module
        $userLicenseMap = Auth::user()->licenses()
            ->with('completion')
            ->get()
            ->keyBy('course_id');

        $activeCourseIds = $userLicenseMap->keys();

        $completions = Auth::user()->completions()
            ->whereIn('course_id', $activeCourseIds)->get();

        $countAttempted = $completions->count();
        $countCompleted = $completions->where('score', '>', 0.79)->count();
        $hours = $completions->where('score', '>', 0.2)->pluck('effort_hours')->toArray();
        $totalHours = array_sum($hours);


        $courses = Course::published()
            ->when(request('course_name'), function ($query, $course_name) {
                $query->where('display_name', 'like', '%' . $course_name . '%');
            })
            ->when(request('course_id'), function ($query, $course_id) {
                $query->where('course_id', $course_id);
            })
            ->orderBy('position')
            ->get(['id', 'display_name', 'effort', 'course_id', 'course_image_uri', 'short_name']);

        return view('site.pages.' . $key, [
            'pageItem' => $itemPage,
            'courses' => $courses,
            'coursesList' => $coursesList,
            'userLicenseMap' => $userLicenseMap,
            'countAttempted' => $countAttempted,
            'countCompleted' => $countCompleted,
            'totalHours' => $totalHours

        ]);
    }

    public function detail($slug){
        $key = 'course-details';
        $itemPage =  app(PageRepository::class)->getPage($key);
        $course = Course::published()->where('short_name', $slug)->first();

        if (!$course || !$itemPage) {
            abort(404);
        }

        $enrolled = Auth::user()->licenses()
            ->where('course_id', $course->id)
            ->exists(); 

        return response(view('site.pages.'.$key,[
            'pageItem' => $itemPage,
            'course' => $course,
            'enrolled' => $enrolled
        ]))
        ->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);

    }

    public function enroll($courseId){

        $course = Course::published()->where('course_id', $courseId)->first();

        $user = Auth::user();

        if (!$course) {
            $site_errors = [
                'title' => 'Sorry',
                'content' => 'The course url seems to have been mistyped',
            ];

            Session::flash('site_errors', $site_errors);
            return redirect()->route('courses');
        }

        if (!isset($_COOKIE['edinstancexid'])) {

            Auth::logout();

            return redirect()->route('login');
        }

        $configLms = config()->get("settings.lms.live");


        $url = $configLms['LMS_BASE'] . '/courses/' . $course->course_id . '/courseware';

        $license = UserLicense::where(['course_id' => $course->id, 'user_id' => Auth::user()->id])->first();
        
        if ($course->start > Carbon::now()) {

            $site_errors = [
                'title' => 'Sorry',
                'content' => 'The course start date is ' . $course->start . '. Please wait and check again later',
            ];

            Session::flash('site_errors', $site_errors);
            return redirect()->route('courses.detail', ['key' => $course->short_name]);
        }


        if (!empty($license)) {

            UserLicense::UpdateOrCreate([
                    'course_id'=> $course->id,
                    'user_id' => $user->id,
                ],
                [
                    'email' => $user->email,
                    'username' => $user->username,
                    'company_id' => $user->company_id,
                    'job_role_id' => $user->job_role_id,
                    'branch_id' => $user->branch_id,
                    'department_id' => $user->department_id,
                    'expired_at' => Carbon::now()->addYears(2)->endOfYear()->toDateTimeString(),
                    'published' => 1
            ]);

            if (isset($license->created_at)) {

                if ($this->edxEnroll($courseId) == false) {
                    $enroll = $this->edxEnroll($courseId);
                    return redirect($url);
                } else {

                    return redirect($url);
                }
            } else {
                $enroll = $this->edxEnroll($courseId);

                if ($enroll) {
                    $license->update(['created_at' => Carbon::today()->toDateTimeString()]);

                    return redirect()->route('courses.detail', $course->short_name);
                }
            }
        } else {

            if ($this->checkEnrollmentStatus($courseId) == true) {

                UserLicense::UpdateOrCreate([
                    'course_id'=> $course->id,
                    'user_id' => $user->id,
                ],
                [
                    'email' => $user->email,
                    'username' => $user->username,
                    'company_id' => $user->company_id,
                    'job_role_id' => $user->job_role_id,
                    'branch_id' => $user->branch_id,
                    'department_id' => $user->department_id,
                    'expired_at' => Carbon::now()->addYears(2)->endOfYear()->toDateTimeString(),
                    'published' => 1
                ]);

                return redirect()->route('courses.detail', $course->short_name)
                    ->with('site_success', [
                        'title'   => 'Enrollment Successful',
                        'content' => 'You have successfully enrolled into this course.',
                    ]);
            }
             else {

                $enroll = $this->edxEnroll($courseId);

                if ($enroll) {

                UserLicense::UpdateOrCreate([
                    'course_id'=> $course->id,
                    'user_id' => $user->id,
                ],
                [
                    'email' => $user->email,
                    'username' => $user->username,
                    'company_id' => $user->company_id,
                    'job_role_id' => $user->job_role_id,
                    'branch_id' => $user->branch_id,
                    'department_id' => $user->department_id,
                    'expired_at' => Carbon::now()->addYears(2)->endOfYear()->toDateTimeString(),
                    'published' => 1
                ]);

                    $site_success = [
                        'title' => 'Congratulations!',
                        'content' => 'You have successfully enrolled for the course. Click on Take Course to start the course.',
                    ];

                    Session::flash('site_success', $site_success);
                    return redirect()->route('courses.detail', $course->short_name);
                } else {

                    $site_errors = [
                        'title' => 'Sorry, course enrollment issues',
                        'content' => 'looks like something went wrong with your course enrollment confirmation.',
                    ];

                    Session::flash('site_errors', $site_errors);
                    return redirect()->route('courses.detail',  $course->short_name);
                }
            }
        }

        $site_sucess = [
            'title'   => 'Enrollment Successful',
            'content' => 'You have successfully enrolled into this course.',
        ];

        Session::flash('site_success', $site_sucess);
        return redirect()->route('courses.detail', $course->short_name);
    }

    private function checkEnrollmentStatus($course_id)
    {
        $edxUser = EdxAuthUser::where('username', Auth::user()->username)->first();
        $token = AccessToken::where('user_id', $edxUser->id)->where('scope', 'LIKE', 'read write email profile')->latest('id')->first();

        $configLms = config()->get("settings.lms.live");
        if (!isset($_COOKIE['edinstancexid'])) {
            Log::info("log at checkenrollment status edx instance is false");
            return false;
        }

        if ($token) {
            if ($token->token != $_COOKIE['edinstancexid']) {

                Auth::logout();

                return redirect()->route('login');
            } elseif ($token->expires < Carbon::now()) {

                Auth::logout();

                return redirect()->route('login');
            }
        } else {
            Auth::logout();

            return redirect()->route('login');
        }


        $client = new \GuzzleHttp\Client(
            [
                'verify' => env('VERIFY_SSL', true),
                'headers' => [
                    'Authorization' => 'Bearer ' . $_COOKIE['edinstancexid']
                ],

            ]
        );

        $request = $client->request(
            'GET',
            $configLms['LMS_BASE'] . '/api/enrollment/v1/enrollment/'
                . Auth::user()->username . ',' . $course_id
        );

        $response = json_decode($request->getBody()->getContents());
        Log::info("log at checkenrollment status response:", [$response, $request->getBody()->getContents(), $request->getStatusCode()]);

        if ($response && $response->is_active == true) {
            $enrollmentStatus = true;
        } else {
            $enrollmentStatus = false;
        }
        //dd($enrollmentStatus);
        Log::info("log at checkenrollment status return:", [$enrollmentStatus]);
        return $enrollmentStatus;
    }



    private function edxEnroll($course_id)
    {
        $configLms = config()->get("settings.lms.live");
        if ($this->checkEnrollmentStatus($course_id)) {
            return ;
        }

        $courseInfoObject = new \stdClass();
        $courseInfoObject->course_id = $course_id;
        $enollAttributesObject = new \stdClass();
        $enollAttributesObject->namespace = 'honor';
        $enollAttributesObject->name = "RBA";
        $enollAttributesObject->value = "RBA";
        $enrollmentInfoObject = new \stdClass();
        $enrollmentInfoObject->user = Auth::user()->username;
        $enrollmentInfoObject->mode = 'honor';
        $enrollmentInfoObject->is_active = true;
        $enrollmentInfoObject->course_details = $courseInfoObject;
        $enrollmentInfoObject->enrollment_attributes = [$enollAttributesObject];
        $enrollClient = new \GuzzleHttp\Client(
            [
                'verify' => env('VERIFY_SSL', true),
                'headers' => [
                    'Authorization' => 'Bearer ' . $_COOKIE['edinstancexid']
                ]
            ]
        );
        Log::info("logs of enrolment object", [$enrollmentInfoObject, Auth::user()]);
        try {
            $response = $enrollClient->request('POST', $configLms['LMS_BASE'] . '/api/enrollment/v1/enrollment', [
                \GuzzleHttp\RequestOptions::JSON => $enrollmentInfoObject
            ]);
            Log::info("log at edx enroll response:", [$response->getStatusCode(), $response->getBody()->getContents(), $enrollmentInfoObject->user]);


            return true; //Toastr::success("You have successfully enrolled into this course");
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            // dd('hapa');
            Log::info("log at edx enroll exception response:", [$e, $response, $enrollmentInfoObject->user]);

            $responseBodyAsString = $response->getBody()->getContents();

            return false;
        }
    }


}
