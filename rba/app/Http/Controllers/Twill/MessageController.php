<?php

namespace App\Http\Controllers\Twill;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use App\Repositories\MessageRepository;
use A17\Twill\Services\Forms\Form;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MessagesExport;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Company;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Branch;
use App\Models\Course;
use App\Models\User;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

class MessageController extends BaseModuleController
{
    protected $moduleName = 'messages';

    protected $filters = [
        'company_id'      => 'company_id',
        'message_type' => 'message_type',
        'department_ids'   => 'department_ids',
        'job_role_ids'     => 'job_role_ids',
        'branch_ids'       => 'branch_ids',
        'audience_ids'     => 'audience_ids',
        'course_ids'       => 'course_ids',
        'send_date'        => 'send_date',
        'sent_status'      => 'sent_status',
    ];

    public const MESSAGE_TYPES = [
        '1' => 'Department',
        '2' => 'Course Messages',
    ];
    public const LEARNERS_COHORTS = [
            'Completed' =>'Completed',
            'Completed but Failed' =>'Completed but Failed',
            'In Progress' =>'In Progress',
            'Yet Started' =>'Yet Started'
    ];

    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->disablePermalink();
    }

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    // public function getForm(TwillModelContract $model): Form
    // {
    //     $form = parent::getForm($model);

    //     $form->add(
    //         Input::make()->name('description')->label('Description')
    //     );

    //     return $form;
    // }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('company_value')->title('Company')
        );

        $table->add(
            Text::make()->field('message_type_value')->title('Message Category')
        );

        $table->add(
            Text::make()->field('audience_value')->title('Target Audience')
        );

        $table->add(
            Text::make()->field('is_recurrent_value')->title('Message Type')->sortable()->sortKey('is_recurrent')->optional()
        );

        $table->add(
            Text::make()->field('sent_value')->title('Sent Status')->sortable()->sortKey('sent_status')->optional()
        );

        $table->add(
            Text::make()->field('frequency_value')->title('Frequency')->sortable()->sortKey('recurrent_frequency')->optional()
        );

        $table->add(
            Text::make()->field('send_date_value')->title('Send Date')->sortable()->sortKey('send_date')->optional()
        );

        return $table;
    }

    protected function indexData($request)
    {
        $CompanyhrRoles = ['Company HR'];
        $messageTypes = collect(self::MESSAGE_TYPES);
        $learnersCohorts = collect(self::LEARNERS_COHORTS);
        $coursesList = Course::published()->orderBy('short_name')->get(['id', 'display_name as title']);
        // dd($coursesList);
        $departmentList = Department::published()->orderBy('title')->get(['id', 'title']);
        
        $user = auth()->guard('twill_users')->user();

        if ($user && in_array($user->role_value, $CompanyhrRoles)) {

            $companyList = Company::published()->orderBy('title')->where('id', $user->company_id)->get(['id', 'title']);
            $branchList = Branch::where('company_id', $user->company_id)->orderBy('title')->get(['id', 'title']);
            $jobRoleList = JobRole::published()->where('company_id', $user->company_id)->orderBy('title')->get(['id', 'title']);
        } else {
            $companyList = Company::published()->orderBy('title')->get(['id', 'title']);
            $branchList = Branch::published()->orderBy('title')->get(['id', 'title']);
            $jobRoleList = JobRole::published()->orderBy('title')->get(['id', 'title']);
        }

        return [
            'company_idList' =>  $companyList,
            'branch_idsList' => $branchList,
            'department_idsList' =>  $departmentList,
            'job_role_idsList' =>  $jobRoleList,
            'message_typeList'=> $messageTypes,
            'audience_idsList' => $learnersCohorts,
            'course_idsList' => $coursesList,
            'send_dateList' => $messageTypes,
        ];
    }

    protected function formData($request)
    {
        $CompanyhrRoles = ['COMPANYHR'];
        $user = auth()->guard('twill_users')->user();

        $itemId = $request->route('message');
        $userList = DB::table('twill_users')->pluck('email','id')->toArray();

        $messageTypeList = self::MESSAGE_TYPES;

        $targets = collect([1 => 'ADMIN', 2 => 'HR']);

        $learnersCohorts = [
            'Completed' =>'Completed',
            'Completed but Failed' =>'Completed but Failed',
            'In Progress' =>'In Progress',
            'Yet Started' =>'Yet Started'
        ];

        $message = Message::where('id', $itemId)->first();
        $selectedTargetList =$message->target_ids ?? [];
        $selectedDepartmentList = $message->department_ids ?? [];
        $selectedBranchList = $message->branch_ids ?? [];
        $selectedUserList = $message->user_ids ?? [];
        $selectedCourseList = $message->course_ids ?? [];
        $selectedAudienceList = $message->audience_ids[0] ?? null;
        $selectedJobRoleList = collect($message->job_role_ids ?? [])->map(fn($id) => (int) $id)->toArray();
        $users = User::published()->get(['id', 'email']);
        $courses = Course::published()->get(['id', 'display_name']);
        $companies = Company::published()->orderBy('title')->get(['id','title']);
        $branches = Branch::published()->orderBy('title')->get(['id','title']);
        $jobroles = Jobrole::published()->orderBy('title')->get(['id','title']);
        $departments = Department::published()->orderBy('title')->get(['id','title']);

        if(in_array($user->role_value, $CompanyhrRoles)){
            $companies = Company::published()->where('id', $user->company_id)->get(['id','title']);  
            $users = User::published()->where('company_id', $user->company_id)->get(['id', 'email']);
            $jobroles = Jobrole::published()->where('company_id', $user->company_id)->orderBy('title')->get(['id','title']);
            $branches = Branch::published()->where('company_id', $user->company_id)->orderBy('title')->get(['id','title']);
        }

        return [
            // 'teamList' => $teamList,
            'selectedDepartmentList' => $selectedDepartmentList,
            'selectedTargetList' => $selectedTargetList,
            'selectedUserList' => $selectedUserList,
            'selectedJobRoleList' => $selectedJobRoleList,
            'selectedBranchList' => $selectedBranchList,
            'selectedAudienceList' => $selectedAudienceList,
            'selectedCourseList' => $selectedCourseList,
            'targetAdmins' => $targets,
            'learnersCohorts' => $learnersCohorts,
            'companies' => $companies,
            'messageTypeList' => $messageTypeList,
            'users' => $users,
            'courses' => $courses,
            'branches' => $branches,
            'jobroles' => $jobroles,
            'departments' => $departments,
            'role'=>$message,
        ];
    }

    public function getDepartmentsForCompany($companyId)
    {
         $departments = Department::published()
            // ->where('company_id', $companyId)
            ->pluck('title', 'id'); // key = id, value = title
            // dd($departments);
        return response()->json($departments);
    }

    public function getJobRolesForDepartment(Request $request)
    {
        $departmentIds = $request->input('department_ids', []);

        $query = JobRole::published();
        if (!empty($departmentIds)) {
            $query->whereIn('department_id', $departmentIds);
        }

        $jobroles = $query->get(['title', 'id'])
            ->map(fn($item) => ['value' => $item->id, 'label' => $item->title]);

        return response()->json(['status' => 'success', 'data' => $jobroles]);
    }

    public function getBranchesForCompany(Request $request)
    {
        $companyIds = $request->input('company_id', []);

        $query = Branch::published();
        if (!empty($companyIds)) {
            $query->whereIn('company_id', $companyIds);
        }

        $branches = $query->get(['id', 'title'])
            ->map(fn($item) => ['value' => $item->id, 'label' => $item->title]);

        return response()->json(['status' => 'success', 'data' => $branches]);
    }


    public function messageUpdate(Request $request, $submoduleId = null)
    {

        $id = $request->input('role_id');
        $input = $request->all();

        $this->repository->update($id, $input);

        Session::flash('status', twillTrans('twill::lang.publisher.save-success'));

        return Redirect::back();
    }

    public function exportMessages(): BinaryFileResponse
    {
        $query = Message::query()->orderBy('created_at', 'desc');

        $user = auth()->guard('twill_users')->user();
        $isHR = $user->is_hr;
        if ($isHR) {
            $query->where('company_id', $user->company_id);
        }

        $this->getMessageRepository()->applyRequestMultiSelectFilters($query);

        $fileName = 'Messages_' . config('app.name') . '_as_at_' . now()->format('d-M-Y_Hi') . '.xlsx';

        return Excel::download(new MessagesExport($query), $fileName);
    }

    protected function getMessageRepository()
    {
        return App::make(MessageRepository::class);
    }

}
