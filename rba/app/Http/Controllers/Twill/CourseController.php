<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use Illuminate\Support\Facades\Session;
use App\Edx\EdxAuthUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\CourseGrading;

class CourseController extends BaseModuleController
{
    protected $moduleName = 'courses';

    /**
     * Name of the index column to use as name column.
     *
     * @var string
     */
    protected $titleColumnKey = 'short_name';

    /**
     * Label of the index column to use as name column.
     *
     * @var string
     */
    protected $titleColumnLabel = 'Short Name';

    /**
     * Label of the title field in forms.
     *
     * @var string
     */
    protected $titleFormLabel = 'Course';

    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = new TableColumns();

        if ($this->getIndexOption('publish')) {
            $columns->add(
                PublishStatus::make()->title(twillTrans('twill::lang.listing.columns.published'))->sortable()->optional()
            );
        }

        $columns = parent::getIndexTableColumns();

        $columns->add(Text::make()->field('display_name')->title('Display Name'));
        $columns->add(Text::make()->field('effort')->title('Course Effort'));
        $columns->add(Text::make()->field('position')->title('Position'));
        $columns->add(Text::make()->field('start')->title('Course Start'));
        $columns->add(Text::make()->field('end')->title('Course End'));
        $columns->add(Text::make()->field('due_date')->title('Deadline'));

        return $columns;
    }

    public function additionalTableActions()
    {
        if (App::environment('local', 'staging')){
            return [];
        } else {
            return [
                'exportAction' => [ // Action name.
                    'name' => 'Sync Courses', // Button action title.
                    'variant' => 'primary', // Button style variant. Available variants; primary, secondary, action, editor, validate, aslink, aslink-grey, warning, ghost, outline, tertiary
                    'size' => 'small', // Button size. Available sizes; small
                    'link' => route('twill.course.sync'), // Button action link.
                    'target' => '', // Leave it blank for self.
                    'type' => 'a', // Leave it blank for "button".
                ]
            ];
        }
    }

    public function getCreateForm(): Form
    {
        return Form::make([
            Input::make()->name('short_name')->label('Course Short Name')->onChange('formatPermalink')->placeholder('Course-Short-Name')->note('e.g. "Time Management Module 2"')->required(),
            Input::make()->name('display_name')->label('Display Name')->placeholder('Course Display Name')->note('No space or special characters allowed e.g. "Time-Management-Module-2"')->required(),
            Input::make()->name('short_description')->label('Description'),
            // temp, to be removed
            Input::make()->name('course_id')->label('*Course_id (To be removed)'),
        ]);
    }

    public function courseSync()
    {

        $edx_courses = $this->edxgetCourses();


        // dd($edx_courses);
        foreach ($edx_courses as $key => $edx_course) {
            $edx_course = (object) $edx_course;
            $course = Course::where('course_id', $edx_course->id)->first();
            Log::info($edx_course->id);

            $slug = str_replace(' ', '-', $edx_course->name);
            $slug2 = str_replace('-–-', '-', $slug);


        if (!empty($course)) {

            DB::table('courses')
                ->where('course_id', $edx_course->id)
                ->update([
                    'course_id' => $edx_course->id,
                    'display_name' => $edx_course->name ?? null,
                    'short_description' => $edx_course->short_description ?? null,
                    'overview' => $edx_course->overview ?? null,
                    'effort' => $edx_course->effort ?? null,
                    'start' => $edx_course->start
                        ? Carbon::parse($edx_course->start)->format('Y-m-d H:i:s')
                        : null,

                    'end' => $edx_course->end
                        ? Carbon::parse($edx_course->end)->format('Y-m-d H:i:s')
                        : null,

                    'enrol_start' => $edx_course->enrollment_start
                        ? Carbon::parse($edx_course->enrollment_start)->format('Y-m-d H:i:s')
                        : null,

                    'enrol_end' => $edx_course->enrollment_end
                        ? Carbon::parse($edx_course->enrollment_end)->format('Y-m-d H:i:s')
                        : null,
                    'due_date' => property_exists($edx_course, 'due_date')
                        ? Carbon::parse($edx_course->due_date)->format('Y-m-d H:i:s')
                        : (
                            $edx_course->end
                                ? Carbon::parse($edx_course->end)->format('Y-m-d H:i:s')
                                : null
                        ),
                    'course_image_uri' => $edx_course->course_image_uri ?? null,
                    'course_video_uri' => $edx_course->course_video_uri ?? null,
                    // 'short_name' => $slug2,

                    'self_paced' => $edx_course->self_paced ?? 1,
                    'mobile_available' => $edx_course->mobile_available,
                    'pass_mark' => $edx_course->pass_mark ?? 0.8,
                    'advanced_modules' => $edx_course->advanced_modules ?? null,
                    'enable_subsection_gating' => $edx_course->enable_subsection_gating ?? 0,
                    'certificates_display_behavior' => $edx_course->certificates_display_behavior ?? 1,

                    // keep internal DB state
                    'status' => $course->status,
                    'published' => $course->published,
                    'course_category_id' => $course->course_category_id,
                    'has_reflections' => $course->has_reflections,
                ]);

        } else {

            Course::create([
                'course_id' => $edx_course->id,
                'display_name' => $edx_course->name,
                'short_description' => $edx_course->short_description ?? null,
                'overview' => $edx_course->overview ?? null,
                'effort' => $edx_course->effort ?? null,
                'start' => $edx_course->start
                    ? Carbon::parse($edx_course->start)->format('Y-m-d H:i:s')
                    : null,

                'end' => $edx_course->end
                    ? Carbon::parse($edx_course->end)->format('Y-m-d H:i:s')
                    : null,

                'enrol_start' => $edx_course->enrollment_start
                    ? Carbon::parse($edx_course->enrollment_start)->format('Y-m-d H:i:s')
                    : null,

                'enrol_end' => $edx_course->enrollment_end
                    ? Carbon::parse($edx_course->enrollment_end)->format('Y-m-d H:i:s')
                    : null,

                'due_date' => property_exists($edx_course, 'due_date')
                    ? $edx_course->due_date
                    : null,
                'course_image_uri' => $edx_course->course_image_uri ?? null,
                'course_video_uri' => $edx_course->course_video_uri ?? null ,
                // 'short_name' => $slug2,
                'self_paced' => $edx_course->self_paced,
                'mobile_available' => $edx_course->mobile_available,
                'pass_mark' => $edx_course->pass_mark ?? 0.8,
                'advanced_modules' => $edx_course->advanced_modules ?? null,
                'enable_subsection_gating' => $edx_course->enable_subsection_gating ?? 0,
                'certificates_display_behavior' => $edx_course->certificates_display_behavior ?? 1,
            ]);
        }



        }

        Session::flash('status', 'Courses have been synced successfully.');
        return redirect('cms/courses');
    }


    private function edxgetCourses()
    {

        $configLms = config()->get("settings.lms.live");
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request(
                'GET',
                $configLms['LMS_BASE'] . '/api/courses/v1/courses/?page=1&page_size=99900000000000000000000',

                // ['verify' => false, 'proxy' => null, 'timeout' => 0, 'connect_timeout' => 0,]
            );



            $courses = json_decode($response->getBody()->getContents())->results;

            foreach (array_slice($courses, 1) as $key => $value) {
                $course = (array) $value;
                // dd($course);

                $settings = $this->getGradingInfo($course);

                $courses[$key] = (array) $courses[$key];

                $course['overview'] = $this->getOverview($course);


                //Remove unwanted fields
                $course['course_video_uri'] = $course['media']->course_video->uri;
                $course['course_image_uri'] = $configLms['LMS_BASE'] . $course['media']->course_image->uri;
                unset($course['media']);
                unset($course['course_id']);
                //Format datetime
                $vowels = array("T", "Z");
                $course['start'] = date('Y-m-d H:m:i', strtotime(str_replace($vowels, " ", $course['start'])));
                $course['end'] = date('Y-m-d H:m:i', strtotime(str_replace($vowels, " ", $course['end'])));
                $course['enrollment_start'] = date('Y-m-d H:m:i', strtotime($course['enrollment_start']));
                $course['enrollment_end'] = date('Y-m-d H:m:i', strtotime($course['enrollment_end']));
                $course['pass_mark'] = $this->getGrading($course);
                $course['advanced_modules'] = (isset($settings['advanced_modules'])) ? implode(',', $settings['advanced_modules']['value']) : '';
                $course['enable_subsection_gating'] = (isset($settings['enable_subsection_gating'])) ? $settings['enable_subsection_gating']['value'] : '';
                $course['mobile_available'] = $course['mobile_available'];
                $course['certificates_display_behavior'] = (isset($settings['certificates_display_behavior'])) ? $settings['certificates_display_behavior']['value'] : '';
                $course['self_paced'] = $course['pacing'];
                //Format time
                // clean up the effort string first
                $effort = preg_replace('/:{2,}/', ':', $course['effort']); // replace double colons with single
                $exploded_effort = explode(":", $effort);

                switch (count($exploded_effort)) {
                    case 3:  // note: should be int not string
                        $course['effort'] = Carbon::createFromTime(
                            (int) $exploded_effort[0],
                            (int) $exploded_effort[1],
                            (int) $exploded_effort[2]
                        )->toTimeString();
                        break;
                    case 2:
                        $course['effort'] = Carbon::createFromTime(
                            0,
                            (int) $exploded_effort[0],
                            (int) $exploded_effort[1]
                        )->toTimeString();
                        break;
                }
                $courses[$key] = $course;
            }

            return $courses;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseJson = $e->getResponse();
            $response = $responseJson->getBody()->getContents();
            dd($response);
        }
    }

    private function getOverview($course)
    {
        $configLms = config()->get("settings.lms.live");
        $client = new \GuzzleHttp\Client();
        try {
            //Get course description
            $request = $client->request('GET', $configLms['LMS_BASE'] . '/api/courses/v1/courses/' . $course['id']);
            $response = json_decode($request->getBody()->getContents());


            return $response->overview;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseJson = $e->getResponse();
            $response = $responseJson->getBody()->getContents();
            return null;
        }
    }



    private function getGradingInfo($course)
    {
        $configLms = config()->get("settings.lms.live");
        $client = new \GuzzleHttp\Client(
            [
              'verify'=>env('VERIFY_SSL',true),
              'headers'=>['Authorization'=>'Bearer '. $_COOKIE['edinstancexid']
            ]
          ]);
        try {
            //Get course description
            $request = $client->request('GET', $configLms['LMS_BASE'] . '/api/grades/v1/gradebook/' . $course['id'].'/grading-info');
            $response = json_decode($request->getBody()->getContents());

            $gradings = (array) $response->assignment_types;

            foreach($gradings as $grading){

              $grade = (array) $grading;

                $g = CourseGrading::where('course_id', $course['id'])->where('assignment_type', $grade['type'])->first();

                if (!empty($g)) {
                    DB::table('course_gradings')
                        ->where('course_id', $course['id'])
                        ->where('assignment_type', $grade['type'])
                        ->update([
                            'course_id' => $course['id'],
                            'assignment_type' =>$grade['type'],
                            'weight' =>$grade['weight']
                        ]);

                }else{

                    CourseGrading::create([
                        'course_id' => $course['id'],
                        'assignment_type' =>$grade['type'],
                        'weight' =>$grade['weight']
                    ]);

                }


            }


            return true;
        } catch (\GuzzleHttp\Exception\ClientException $e) {


            $responseJson = $e->getResponse();
            $response = $responseJson->getBody()->getContents();

            return false;
        }
    }



    private function getGrading($course)
    {
        $configLms = config()->get("settings.lms.live");
        $userinfo = array();

        $user = EdxAuthUser::where('username', Auth::user()->username)->first();
        $userinfo = ['id' => $user->id, 'username' => $user->username, 'is_staff' => $user->is_staff, 'is_superuser' => $user->is_superuser, 'email' => $user->email, 'is_active' => $user->is_active];

        // use Client ti process a get method from the studio

        $enrollmentInfoObject = new \stdClass();
        $enrollmentInfoObject->user = json_encode($userinfo);

        $enrollClient = new \GuzzleHttp\Client(
            [
                'verify' => env('VERIFY_SSL', true),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Origin' => $configLms['CMS_BASE'],
                    'Referer' => $configLms['CMS_BASE'],
                    'X-Requested-With' => ' XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $_COOKIE['edinstancexid'],

                ]
            ]
        );


        try {
            $response = $enrollClient->request('GET', $configLms['CMS_BASE'] . '/api/contentstore/v1/course_grading/' . $course['id']);

            $res = json_decode($response->getBody()->getContents(), true);

            $minimumGrade = $res['course_details']['minimum_grade_credit']
                ?? $res['course_details']['grade_cutoffs']['Pass']
                ?? null;
            // dd($minimumGrade);

            return $minimumGrade;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Grading API client error: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error('Grading API error: ' . $e->getMessage());
            return false;
        }
    }

    private function getAdvanced($course)
    {
        $configLms = config()->get("settings.lms.live");
        $userinfo = array();

        $user = EdxAuthUser::where('username', Auth::user()->username)->first();
        $userinfo = ['id' => $user->id, 'username' => $user->username, 'is_staff' => $user->is_staff, 'is_superuser' => $user->is_superuser, 'email' => $user->email, 'is_active' => $user->is_active];


        // use Client ti process a get method from the studio

        $enrollmentInfoObject = new \stdClass();
        $enrollmentInfoObject->user = json_encode($userinfo);

        $enrollClient = new \GuzzleHttp\Client(
            [
                'verify' => env('VERIFY_SSL', true),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Origin' => $configLms['CMS_BASE'],
                    'Referer' => $configLms['CMS_BASE'],
                    'X-Requested-With' => ' XMLHttpRequest',

                ]
            ]
        );

        try {
            $response = $enrollClient->request('GET', $configLms['CMS_BASE'] . '/course/settings/advanced/' . $course['id'], [
                \GuzzleHttp\RequestOptions::JSON => $enrollmentInfoObject
            ]);



            $res = json_decode($response->getBody()->getContents(), true);


            return $res;
        } catch (\GuzzleHttp\Exception\ClientException $e) {


            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

}
