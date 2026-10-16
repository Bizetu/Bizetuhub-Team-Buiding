<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Services\Listings\Filters\TableFilters;
use App\Services\Listings\Filters\MultiSelectFilter;
use A17\Twill\Models\Contracts\TwillModelContract;
use App\Services\Listings\Filters\DateRangeFilter;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\SerialNumberRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Exports\CertifiedUsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SerialNumber;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Department;
use App\Models\Branch;

class SerialNumberController extends BaseModuleController
{
    protected $moduleName = 'serialNumbers';
    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected $titleColumnKey = 'email';
    protected $filters = [
        'company_id'      => 'company_id',
        'department_id'   => 'department_id',
        'job_role_id'     => 'job_role_id',
        'branch_id'     => 'branch_id',
        'download_status' => 'download_status',
        'downloaded_at'   => 'downloaded_at',
    ];

    protected $defaultIndexOptions = [
        'permalink' => true,
        'disableCreate' => true,
        'create' => false,
        'edit' => true,
        'publish' => false,
        'bulkPublish' => false,
        'delete' => false,
        'bulkDelete' => false,
    ];

    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->disableCreate();
    }

    public function __construct(Application $app, Request $request, SerialNumber $repository)
    {
        parent::__construct($app, $request);

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->repository = $this->getSerialNumberRepository();
            return $next($request);
        });
    }

    protected function indexData($request)
    {
        $CompanyhrRoles = ['COMPANYHR'];
        $downloadStatus = collect([1 => 'Downloaded', 2 => 'Yet to download']);

        if (auth()->guard('twill_users')->user() && in_array(auth()->guard('twill_users')->user()->roleValue, $CompanyhrRoles)) {

            $company = Company::published()->orderBy('title')->where('id', Auth::user()->company_id)->get();
            $branchList = Branch::where('company_id')->orderBy('title')->get();
            $jobRoleList = JobRole::published()->where('company_id', Auth::user()->company_id)->orderBy('title')->get();
            $departmentList = Department::published()->where('company_id', Auth::user()->company_id)->orderBy('title')->get();
        } else {
            $company = Company::published()->orderBy('title')->get();
            $branchList = Branch::published()->orderBy('title')->get();
            $jobRoleList = JobRole::published()->orderBy('title')->get();
            $departmentList = Department::published()->orderBy('title')->get();
        }

        return [
            'company_idList' =>  $company,
            'department_idList' =>  $departmentList,
            'job_role_idList' =>  $jobRoleList,
            'branch_idList' => $branchList,
            'download_statusList' => $downloadStatus,
            'downloaded_atList'=> $downloadStatus,
        ];
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(
            Text::make()->field('company')->title('Company')
        );
        $table->add(
            Text::make()->field('department')->title('Department')
        );
        $table->add(
            Text::make()->field('jobrole')->title('Job Role')
        );
        $table->add(
            Text::make()->field('branch')->title('Branch')
        );
        $table->add(
            Text::make()->field('download_status')->title('Download Status')
        );
        $table->add(
            Text::make()->field('average_score')->title('Average Score')
        );
        $table->add(
            Text::make()->field('downloaded_at')->title('Downloaded On')
        );

        return $table;
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
        ];
    }

    public function getFilterAll()
    {

        $superRole = ['SUPERADMIN'];
        $adminRoles = ['Admin'];
        $CompanyhrRoles = ['COMPANYHR'];

        if (auth()->guard('twill_users')->user() && (in_array(auth()->guard('twill_users')->user()->roleValue, $superRole) || in_array(auth()->guard('twill_users')->user()->roleValue, $adminRoles) || in_array(auth()->guard('twill_users')->user()->roleValue, $CompanyhrRoles))) {
            $filters = [
                'company_id' => 'company_id',
                'department_id' => 'department_id',
                'job_role_id' => 'job_role_id',
                'branch_id' => 'branch_id',
                'download_status' => 'download_status',
                'downloaded_at'=> '',
            ];
        }
        if (auth()->guard('twill_users')->user() && (in_array(auth()->guard('twill_users')->user()->roleValue, $CompanyhrRoles))) {
            $filters = [
                'company_id' => 'company_id',
                'department_id' => 'department_id',
                'job_role_id' => 'job_role_id',
                'branch_id' => 'branch_id',
                'download_status' => 'download_status',
                'downloaded_at'=> '',
            ];
        }

        return $filters;
    }

    protected function getSerialNumberRepository()
    {
        return App::make("App\\Repositories\\SerialNumberRepository");
    }

    public function exportCertifiedUsers(Request $request)
    {
        $scopes = json_decode($request->get('filter'), true) ?? [];
        $users = $this->repository->export($scopes);

        $title = 'Certified_Users_Export_RBA_as_at_' . now()->addHours(3)->translatedFormat('jS_F_Y_H:i:s') . '.xlsx';

        if(auth()->user()->role == "COMPANYHR"){
            $company = Company::where('id', auth()->user()->company_id)->value('title');
            $title = $company . ' Certified_Users_Export_as_at_' . now()->addHours(3)->translatedFormat('jS_F_Y_H:i:s') . '.xlsx';
        }

        $response = Excel::download(new CertifiedUsersExport($users), $title, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();

        return $response;
    }
}
