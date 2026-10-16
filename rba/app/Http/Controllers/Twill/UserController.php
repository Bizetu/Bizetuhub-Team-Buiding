<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Http\Controllers\Admin\UserController as TwillUserController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use A17\Twill\Services\Listings\Filters\TableFilters;
use App\Services\Listings\Filters\MultiSelectFilter;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Listings\TableColumn;
use A17\Twill\Services\Forms\Fields\Input;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Twill\UserRequest;
use Illuminate\Support\Facades\Response;
use A17\Twill\Facades\TwillPermissions;
use App\Exports\UsersArchivingTemplate;
use Illuminate\Support\Facades\Session;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Enums\PermissionLevel;
use Illuminate\Support\Facades\Auth;
use A17\Twill\Facades\TwillCapsules;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use A17\Twill\Services\Forms\Form;
use Illuminate\Support\Collection;
use A17\Twill\Helpers\FlashLevel;
use Illuminate\Http\JsonResponse;
use A17\Twill\Models\Permission;
use App\Models\CompletionReport;
use App\Imports\ArchiveUsers;
use App\Imports\RestoreUsers;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Branch;
use App\Models\Course;
use App\Models\User;
use Exception;

class UserController extends TwillUserController
{
    protected $namespace = 'App';
    protected $moduleName = 'users';
    protected $titleColumnKey = 'name';
    protected $indexOptions = ['create' => false];
    protected $indexWith = ['company', 'department', 'branch', 'jobRole'];
    protected $defaultOrders = ['created_at' => 'desc'];

    protected function getIndexOption($option, $item = null)
    {
        if (in_array($option, ['publish', 'bulkEdit', 'create'])) {
            return $this->authFactory->guard('twill_users')->user()->can('edit-users');
        }

        return parent::getIndexOption($option);
    }

    protected function indexData($request)
    {
        return [
            'defaultFilterSlug' => 'activated',
            'create' => false,
            'roleList' => $this->getRoleList(),
        ];
    }

    private function getRoleList()
    {
        if (config('twill.enabled.permissions-management')) {
            return twillModel('role')::accessible()->published()->get()->map(function ($role) {
                return ['value' => $role->id, 'label' => $role->name];
            })->toArray();
        }

        return collect(TwillPermissions::roles()::toArray())->map(function ($item, $key) {
            return ['value' => $key, 'label' => $item];
        })->values()->toArray();
    }

    private function getGroupPermissionMapping()
    {
        if (config('twill.enabled.permissions-management')) {
            return twillModel('group')::with('permissions')->get()
                ->mapWithKeys(function ($group) {
                    return [$group->id => $group->permissions];
                })->toArray();
        }

        return [];
    }

    private function getGroups()
    {
        if (config('twill.enabled.permissions-management')) {
            // Forget first one because it's the "Everyone" group and we don't want to show it inside admin.
            return twillModel('group')::with('permissions')->pluck('name', 'id')->forget(1);
        }

        return [];
    }

    private function getPermissionModules()
    {
        if (config('twill.enabled.permissions-management')) {
            return Permission::permissionableParentModuleItems();
        }

        return [];
    }

    protected function indexItemData($item)
    {
        $data = parent::indexItemData($item);
        $data['published'] = (bool) $item->published;
        return $data;
    }

    public function getIndexTableColumns(): TableColumns
    {
        $tableColumns = TableColumns::make();
        if ($this->getIndexOption('publish')) {
            $tableColumns->add(
                PublishStatus::make()->title(twillTrans('twill::lang.listing.columns.published'))->sortable()->optional()
            );
        }

        $tableColumns->add(Text::make()->field($this->titleColumnKey)->linkToEdit()->sortable());
        $tableColumns->add(Text::make()->field('email')->title('Email')->sortable());
        $tableColumns->add(Text::make()->field('role_value')->title('Role')->sortable()->sortKey('role_id')->optional());
        $tableColumns->add(Text::make()->field('gender_value')->title('Gender')->sortable()->sortKey('gender_id')->optional());
        $tableColumns->add(Text::make()->field('company_name_value')->title('Institution')->sortable()->sortKey('company_id')->optional());
        $tableColumns->add(Text::make()->field('department_name_value')->title('Department')->sortable()->sortKey('department_id')->optional());
        $tableColumns->add(Text::make()->field('branch_name_value')->title('Branch')->sortable()->sortKey('branch_id')->optional());
        $tableColumns->add(Text::make()->field('job_role_name_value')->title('Job Role')->sortable()->sortKey('job_role_id')->optional());
        $tableColumns->add(Text::make()->field('last_login_at_value')->title('Last Login')->sortable()->sortKey('last_login_at')->optional());
        $tableColumns->add(Text::make()->field('date_created')->title('Registration Date')->sortable()->sortKey('created_at')->optional());

        return $tableColumns;
    }

    public function additionalTableActions(): array
    {
        return [
            'resetFiltersAction' => [
                'name'    => 'Clear Filters',
                'variant' => 'ghost', // Button style variant. Available variants; primary, secondary, action, editor, validate, aslink, aslink-grey, warning, ghost, outline, tertiary
                'size'    => 'small',
                'link'    => route('twill.users.index'),
                'target'  => '',
                'type'    => 'a',
            ],
            'exportAction' => [
                'name'    => 'Export',
                'variant' => 'primary',
                'size'    => 'small',
                'link'    => route('twill.export.users'),
                'target'  => '',
                'type'    => 'a',
            ],
            'archiveUsersAction' => [
                'name'    => 'Archive',
                'variant' => 'warning',
                'size'    => 'small',
                'link'    => route('twill.archiveUsersForm'),
                'target'  => '',
                'type'    => 'a',
            ],
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     */
    protected function formData($request)
    {
        $currentUser = $this->authFactory->guard('twill_users')->user();
        $with2faSettings = $this->config->get('twill.enabled.users-2fa') && $currentUser->id == $request->route('user');

        if ($with2faSettings) {
            $currentUser->generate2faSecretKey();

            $qrCode = $currentUser->get2faQrCode();
        }

        // Get user thumbnail (fixme because this always return the fallback blank image)
        if ($this->config->get('twill.enabled.users-image')) {
            $user = $this->repository->getById($request->route('user'));
            $role = head(array_keys($user->mediasParams));
            $crop = head(array_keys(head($user->mediasParams)));
            $params = ['w' => 100, 'h' => 100];
            $titleThumbnail = $user->cmsImage($role, $crop, $params);
        }

        if (TwillPermissions::levelIs(PermissionLevel::LEVEL_ROLE_GROUP_ITEM)) {
            $permissionsData = [
                'permissionModules' => $this->getPermissionModules(),
            ];
        }
        $isHr = $currentUser->role_value === "Company HR" || $currentUser->is_company_admin === 1 || $currentUser->role_id === 3;

        $companyOptions = collect($isHr ? Company::published()->where('id', $currentUser->company_id)->pluck('title', 'id')->toArray() : Company::published()->pluck('title', 'id')->toArray())->map(function ($item, $key) {
            return ['value' => $key, 'label' => $item];
        })->values()->toArray();
        $branchOptions = collect($isHr ? Branch::published()->where('id', $currentUser->company_id)->pluck('title', 'id')->toArray() : Branch::published()->pluck('title', 'id')->toArray())->map(function ($item, $key) {
            return ['value' => $key, 'label' => $item];
        })->values()->toArray();
        $departmentOptions = collect(Department::published()->pluck('title', 'id')->toArray())->map(function ($item, $key) {
            return ['value' => $key, 'label' => $item];
        })->values()->toArray();
        $jobRoleOptions = collect(JobRole::published()->pluck('title', 'id')->toArray())->map(function ($item, $key) {
            return ['value' => $key, 'label' => $item];
        })->values()->toArray();

        $userId = $request->route('user');
        $completionReport = $this->getCourseUptakeReport($userId);

        return [
            'roleList' => $this->getRoleList(),
            'isHr' => $isHr,
            'departmentOptions' => $departmentOptions,
            'branchOptions' => $branchOptions,
            'jobRoleOptions' => $jobRoleOptions,
            'companyOptions' => $companyOptions,
            'completionReport' => $completionReport,
            'titleThumbnail' => $titleThumbnail ?? null,
            'with2faSettings' => $with2faSettings,
            'qrCode' => $qrCode ?? null,
            'groupPermissionMapping' => $this->getGroupPermissionMapping(),
            'groupOptions' => $this->getGroups(),
        ] + ($permissionsData ?? []);
    }

    protected function getCourseUptakeReport($userId)
    {
        $user = User::findOrFail($userId);
        $completions = CompletionReport::where('user_id', $user->id)->whereHas('courseRelationship')->groupBy('id')->get();
        $mandatory = Course::published()->get();

        return [
            'user' => $user,
            'completions' => $completions,
            'mandatory' => $mandatory,
            'job_role' => $user?->job_role_name_value ?? 'Undefined',
        ];
    }

    public function filters(): TableFilters
    {
        $isHR = auth()->guard('twill_users')->user()->is_hr;

        $tableFilters = TableFilters::make();
        $genders = [
            1 => 'Female',
            2 => 'Male',
            3 => 'Undefined'
        ];

        if (! $isHR) {
            $tableFilters->add(
                MultiSelectFilter::make()
                    ->queryString('company_id')
                    ->label('Company')
                    ->options(collect(Company::orderBy('title')->pluck('title', 'id')->toArray()))
                    ->apply(fn (Builder $b, array $v) => $b->whereIn('company_id', $v))
            );
        }

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('gender_id')
                ->label('Gender')
                ->options(collect($genders))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('gender_id', $v))
        );

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('job_role_id')
                ->label('JobRole')
                ->options(collect(JobRole::orderBy('title')->pluck('title', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('job_role_id', $v))
        );

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('department_id')
                ->label('Department')
                ->options(collect(Department::orderBy('title')->pluck('title', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('department_id', $v))
        );

        $tableFilters->add(
            MultiSelectFilter::make()
                ->queryString('branch_id')
                ->label('Branch')
                ->options(collect(Branch::published()->orderBy('title')->pluck('title', 'id')->toArray()))
                ->apply(fn (Builder $b, array $v) => $b->whereIn('branch_id', $v))
        );

        return $tableFilters;
    }

    public function export()
    {
        $query = User::query()->orderBy('created_at', 'desc');

        $user = auth()->guard('twill_users')->user();
        $isHR = $user->is_hr;
        if ($isHR) {
            $query->where('company_id', $user->company_id);
        }

        $referer = $this->request->header('referer');
        if ($referer) {
            parse_str(parse_url($referer, PHP_URL_QUERY), $queryParams);

            if (!empty($queryParams['filter'])) {
                $filter = json_decode($queryParams['filter'], true) ?? [];

                if (!empty($filter['status']) && $filter['status'] !== 'all') {
                    $query->where('status', $filter['status']);
                }

                if (!empty($filter['search'])) {
                    $query->where(function ($q) use ($filter) {
                        $q->where('username', 'like', '%' . $filter['search'] . '%')
                          ->orWhere('email', 'like', '%' . $filter['search'] . '%');
                    });
                }

                $multiSelectColumns = ['branch_id','branch_id','department_id','job_role_id','company_id'];

                foreach ($multiSelectColumns as $col) {
                    if (!empty($filter[$col]) && $filter[$col] !== 'all') {
                        $query->whereIn($col, (array) $filter[$col]);
                    }
                }
            }
        }

        $fileName = 'Users_' . config('app.name') . '_as_at_' . now()->format('d-M-Y_Hi') . '.xlsx';

        return Excel::download(new UsersExport($query), $fileName);
    }

    public function archiveUsersForm()
    {
        return view('twill.users.archive');
    }

    public function template()
    {
        $fileName = 'User_Archiving_Template_' . config('app.name') . '_' . now() . '.xlsx';
        return Excel::download(new UsersArchivingTemplate(), $fileName);
    }

    public function archiveUsers(Request $request)
    {
        try
        {
            $request->validate([
                'file' => [
                    'required',
                    'file',
                    'max:2048',
                ]
            ], [
                'file.required' => 'Please select a file to upload.',
                'file.file' => 'The uploaded file is not valid.',
                'file.max' => 'The file size must not exceed 2MB.',
            ]);

            $file = $request->file('file');
            if (!$file->isValid())
            {
                return back()->withInput()->withErrors('The uploaded file is corrupted or invalid. Please try again.');
            }

            Excel::import(new ArchiveUsers, $file);

            $status = session()->get('archived');

            switch ($status) {

                case 'Empty_File':
                    return back()->withErrors('Unable to process the file. Please ensure it is not empty and try again.');

                case 'Email_Column_Missing':
                    return back()->withErrors('File must contain a column labelled "email" as the first and only column. Please check the file and try again.');

                case 'Trashed':
                    return back()->withErrors('Cannot archive archived users. Please check the file and try again.');

                case 'Failed':
                    return back()->withErrors('Something went wrong. Kindly check the file and try again.');

                case 'Cannot_Self_Archive':
                    return back()->withErrors('Cannot Self Archive. Kindly check the file and try again.');

                case 'Unauthorized':
                    return back()->withErrors('You are not authorized to archive users from other companies. Please check the file and try again.');

                case 'Success':
                    session()->flash('status', 'User(s) archived successfully.');
                    return back();

                case 'Trashed_Success':
                    session()->flash('status', 'Operation success with some of the users having already been archived.');
                    return back();

                default:
                    return back()->withErrors('Unexpected error occurred during deactivation. Kindly check the file and try again');
            }

        }
        catch (Exception $e)
        {
            Log::channel('archive')->error('Archive Users Import Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->withErrors('An error occurred while processing the file. Please try again or contact support if the problem persists.');
        }
    }

    public function restoreUsers(Request $request)
    {
        try
        {
            $request->validate([
                'restore_file' => [
                    'required',
                    'file',
                    'max:2048',
                ]
            ], [
                'restore_file.required' => 'Please select a file to upload.',
                'restore_file.file' => 'The uploaded file is not valid.',
                'restore_file.max' => 'The file size must not exceed 2MB.',
            ]);

            $file = $request->file('restore_file');
            if (!$file->isValid())
            {
                return back()->withInput()->withErrors('The uploaded file is corrupted or invalid. Please try again.');
            }

            Excel::import(new RestoreUsers, $file);

            $restoreStatus = session()->get('restored');

            if ($restoreStatus === 'Yes')
            {
                session()->flash('status', 'User(s) restored successfully.');
                return back();
            }
            elseif ($restoreStatus === 'Empty')
            {
                return back()->withErrors('Unable to process the file. Please ensure it is not empty and try again.');
            }
            elseif ($restoreStatus === 'Email')
            {
                return back()->withErrors('File must contain email column. Please check the file and try again.');
            }
            elseif ($restoreStatus === 'NoMatches')
            {
                return back()->withErrors('No users were restored. Please check the file and try again.');
            }
            elseif ($restoreStatus === 'Active')
            {
                return back()->withErrors('Some users in the file are already active. Please check the file and try again.');
            }
            elseif ($restoreStatus === 'Failed')
            {
                return back()->withErrors('An error occurred while restoring users. Please try again.');
            }
            elseif ($restoreStatus === 'Unauthorized')
            {
                return back()->withErrors('You are not authorized to restore users from other companies. Please check the file and try again.');
            }
            elseif ($restoreStatus === 'Active_Success')
            {
                session()->flash('status', 'Operation success with some of the users already being active.');
                return back();
            }
            else
            {
                return back()->withErrors('An unexpected error occurred. Please try again.');
            }

        }
        catch (Exception $e)
        {
            Log::channel('archive')->error('Restore Users Import Error: ' . $e->getMessage(), [
                'restore_file' => $request->file('restore_file') ? $request->file('restore_file')->getClientOriginalName() : 'No file',
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->withErrors('An error occurred while processing the file. Please try again or contact support if the problem persists.');
        }
    }

    public function update(int|TwillModelContract $id, ?int $submoduleId = null): JsonResponse
    {
        [$item, $id] = $this->itemAndIdFromRequest($id);

        $this->authorizeOption('edit', $item);

        $input = $this->request->all();

        if (isset($input['cmsSaveType']) && $input['cmsSaveType'] === 'cancel') {
            return $this->respondWithRedirect(
                moduleRoute(
                    $this->moduleName,
                    $this->routePrefix,
                    'edit',
                    [Str::singular($this->moduleName) => $id]
                )
            );
        }

        $this->performUpdate($item);

        if (isset($input['cmsSaveType'])) {
            if (Str::endsWith($input['cmsSaveType'], '-close')) {
                return $this->respondWithRedirect($this->getBackLink());
            }

            if (Str::endsWith($input['cmsSaveType'], '-new')) {
                if ($this->getIndexOption('skipCreateModal')) {
                    return $this->respondWithRedirect(
                        moduleRoute(
                            $this->moduleName,
                            $this->routePrefix,
                            'create'
                        )
                    );
                }

                return $this->respondWithRedirect(
                    moduleRoute(
                        $this->moduleName,
                        $this->routePrefix,
                        'index',
                        ['openCreate' => true]
                    )
                );
            }

            if ($input['cmsSaveType'] === 'restore') {
                Session::flash('status', twillTrans('twill::lang.publisher.restore-success'));

                return $this->respondWithRedirect(
                    moduleRoute(
                        $this->moduleName,
                        $this->routePrefix,
                        'edit',
                        [Str::singular($this->moduleName) => $id]
                    )
                );
            }
        }

        if ($this->moduleHas('revisions')) {
            return Response::json([
                'message' => twillTrans('twill::lang.publisher.save-success'),
                'variant' => FlashLevel::SUCCESS,
                'revisions' => $item->refresh()->revisionsArray(),
            ]);
        }

        return $this->respondWithSuccess(twillTrans('twill::lang.publisher.save-success'));
    }

    private function itemAndIdFromRequest(TwillModelContract|int $id): array
    {
        if ($id instanceof TwillModelContract) {
            $item = $id;
            $id = $item->id;
        } else {
            $parameter = Str::singular(Str::afterLast($this->moduleName, '.'));
            $id = (int) $this->request->route()->parameter($parameter, $id);
            $item = $this->repository->getById($id, $this->formWith, $this->formWithCount);
        }

        return [
            $item,
            $id,
        ];
    }

    protected function performUpdate($item): void
    {
        $formRequest = $this->validateFormRequest();
        $data = $formRequest->all();

        if (Str::startsWith($data['cmsSaveType'] ?? '', 'draft-revision')) {
            $data['published'] = false;

            $this->repository->createRevisionIfNeeded($item, $data);
        } else {
            $this->repository->update($item->id, $data);

            activity()->performedOn($item)->log('updated');

            $this->fireEvent();
        }
    }

    /**
     * @return \A17\Twill\Http\Requests\Admin\Request
     */
    protected function validateFormRequest()
    {
        $unauthorizedFields = Collection::make($this->fieldsPermissions)->filter(function ($permission, $field) {
            return Auth::guard('twill_users')->user()->cannot($permission);
        })->keys();

        $unauthorizedFields->each(function ($field) {
            $this->request->offsetUnset($field);
        });

        return App::make($this->getFormRequestClass());
    }

    public function getFormRequestClass()
    {
        return UserRequest::class;
    }
}
