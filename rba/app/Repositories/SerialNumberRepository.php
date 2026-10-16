<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\SerialNumber;
use App\Models\EligibleUser;
use App\Models\User;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class SerialNumberRepository extends ModuleRepository
{

    public function __construct(EligibleUser $model, SerialNumber $serialNumber)
    {
        $this->model = $model;  // for listing will extend user model
        $this->serialNumber = $serialNumber; //for creating and updating
    }

    public function convertToArray($col)
    {
        if (empty($col) || $col === 'all') {
            return [];
        }

        // If it's a comma-separated string
        return $col;
    }

    public function get($with = [], $scopes = [], $orders = [], $perPage = 20, $forcePagination = false, $appliedFilters = []): LengthAwarePaginator|Collection
    {
        $request = request();
        $filter    = json_decode(request()->query('filter'), true);
        $branchIds = $this->convertToArray($filter['branch_id'] ?? []);
        $departmentIds = $this->convertToArray($filter['department_id'] ?? []);
        $companyIds = $this->convertToArray($filter['company_id'] ?? []);
        $jobroleIds = $this->convertToArray($filter['job_role_id'] ?? []);
        $downloadStatusIds = $this->convertToArray($filter['download_status'] ?? []);

        $user = Auth::user();
        $query = $this->model->newQuery()
            ->from(DB::raw('twill_users'))
            ->leftJoin('serial_numbers as sn', function ($join) {
                $join->on('twill_users.id', '=', 'sn.user_id')
                    ->where('sn.certificate_type', 1);
            })
            ->leftJoin('companies', 'twill_users.company_id', '=', 'companies.id')
            ->leftJoin('job_roles', 'twill_users.job_role_id', '=', 'job_roles.id')
            ->leftJoin('branches', 'twill_users.branch_id', '=', 'branches.id')
            ->leftJoin('departments', 'twill_users.department_id', '=', 'departments.id')
            // ->when($user->is_company_admin, function ($q) use ($user) {
            //     $q->where('twill_users.company_id', $user->company_id);
            // })
            ->when($companyIds, function ($q) use ($companyIds) {
                $q->whereIn('twill_users.company_id', $companyIds);
            })
            ->when($jobroleIds, function ($q) use ($jobroleIds) {
                $q->whereIn('twill_users.job_role_id', $jobroleIds);
            })
            ->when($branchIds, function ($q) use ($branchIds) {
                $q->whereIn('twill_users.branch_id', $branchIds);
            })
            ->when($departmentIds, function ($q) use ($departmentIds) {
                $q->whereIn('twill_users.department_id', $departmentIds);
            })
            ->when($request->filled('range'), function ($q) use ($request) {
                $range = explode(' - ', $request->input('range'));
                if (count($range) == 2) {
                    $q->whereBetween('sn.created_at', [
                        Carbon::parse($range[0])->startOfDay(),
                        Carbon::parse($range[1])->endOfDay(),
                    ]);
                }
            })
            ->when(!empty($downloadStatusIds), function ($q) use ($downloadStatusIds) {
                $q->where(function ($q) use ($downloadStatusIds) {
                    if (in_array(1, $downloadStatusIds)) {
                        $q->whereNotNull('sn.created_at');
                    }
                    if (in_array(2, $downloadStatusIds)) {
                        $q->orWhereNull('sn.created_at');
                    }
                });
            })
            ->when(!empty($filter['search']), function ($q) use ($filter) {
                $search = $filter['search'];

                $q->where(function ($q) use ($search) {
                    $q->where('twill_users.email', 'like', "%{$search}%")
                    ->orWhere('twill_users.name', 'like', "%{$search}%")
                    ->orWhere('companies.title', 'like', "%{$search}%")
                    ->orWhere('job_roles.title', 'like', "%{$search}%")
                    ->orWhere('departments.title', 'like', "%{$search}%")
                    ->orWhere('branches.title', 'like', "%{$search}%");
                });
            })
            ->select([
                'twill_users.id as id',
                'twill_users.email as email',
                'companies.title as company',
                'job_roles.title as jobrole',
                'branches.title as branch',
                'departments.title as department',
                'sn.created_at as created_at', // ✅ disambiguate
                DB::raw("DATE_FORMAT(sn.created_at, '%Y-%m-%d %H:%i:%s') as downloaded_at"),
                'sn.certificate_type as certificate_type',
                DB::raw("
                    COALESCE(
                        NULLIF(
                            TRIM(
                                CONCAT(
                                    COALESCE(twill_users.first_name, ''),
                                    ' ',
                                    COALESCE(twill_users.last_name, '')
                                )
                            ),
                            ''
                        ),
                        'N/A'
                    ) as name
                "),
                DB::raw("CASE WHEN sn.created_at IS NOT NULL THEN 'Downloaded' ELSE 'Yet to Download' END as download_status"),
                DB::raw("CONCAT(ROUND(twill_users.average_score * 100, 1), '%') as average_score"),
                DB::raw("1 as published"),
            ])
            ->orderBy('sn.created_at', 'DESC');


        $query = $this->filter($query, $scopes);

        $query = $this->order($query, $orders);
        

        if (!$forcePagination && $this->model instanceof Sortable) {
            return $query->ordered()->get();
        }

        if ($perPage == -1) {
            return $query->get();
        }
        // dd($query->get());

        return $query->paginate($perPage);
    }

    public function export($scopes = [])
    {
        // dd('EXPORT METHOD HIT');
        $request = request();
        $user = Auth::user();

        //Decode Twill filters
        $filters = json_decode($request->input('filter', '{}'), true);
        $branchIds = $this->convertToArray($filters['branch_id'] ?? []);
        $departmentIds = $this->convertToArray($filters['department_id'] ?? []);
        $companyIds = $this->convertToArray($filters['company_id'] ?? []);
        $jobroleIds = $this->convertToArray($filters['job_role_id'] ?? []);
        $downloadStatusIds = $this->convertToArray($filters['download_status'] ?? []);

        return $this->model->newQuery()
            ->from('twill_users')
            ->leftJoin('serial_numbers as sn', function ($join) {
                $join->on('twill_users.id', '=', 'sn.user_id')
                    ->where('sn.certificate_type', 1);
            })
            ->leftJoin('companies', 'twill_users.company_id', '=', 'companies.id')
            ->leftJoin('job_roles', 'twill_users.job_role_id', '=', 'job_roles.id')
            ->leftJoin('branches', 'twill_users.branch_id', '=', 'branches.id')
            ->leftJoin('departments', 'twill_users.department_id', '=', 'departments.id')
            ->when($companyIds, function ($q) use ($companyIds) {
                $q->whereIn('twill_users.company_id', $companyIds);
            })
            ->when($jobroleIds, function ($q) use ($jobroleIds) {
                $q->whereIn('twill_users.job_role_id', $jobroleIds);
            })
            ->when($branchIds, function ($q) use ($branchIds) {
                $q->whereIn('twill_users.branch_id', $branchIds);
            })
            ->when($departmentIds, function ($q) use ($departmentIds) {
                $q->whereIn('twill_users.department_id', $departmentIds);
            })
            ->when($request->filled('range'), function ($q) use ($request) {
                $range = explode(' - ', $request->input('range'));
                if (count($range) == 2) {
                    $q->whereBetween('sn.created_at', [
                        Carbon::parse($range[0])->startOfDay(),
                        Carbon::parse($range[1])->endOfDay(),
                    ]);
                }
            })
            ->when(!empty($downloadStatusIds), function ($q) use ($downloadStatusIds) {
                $q->where(function ($q) use ($downloadStatusIds) {
                    if (in_array(1, $downloadStatusIds)) {
                        $q->whereNotNull('sn.created_at');
                    }
                    if (in_array(2, $downloadStatusIds)) {
                        $q->orWhereNull('sn.created_at');
                    }
                });
            })
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $search = '%' . $filters['search'] . '%';
                $q->where(function ($q) use ($search) {
                    $q->where('twill_users.email', 'like', $search)
                    ->orWhere('twill_users.first_name', 'like', $search)
                    ->orWhere('twill_users.last_name', 'like', $search)
                    ->orWhereRaw("CONCAT(twill_users.first_name, ' ', twill_users.last_name) like ?", [$search])
                    ->orWhere('companies.title', 'like', $search)
                    ->orWhere('job_roles.title', 'like', $search);
                });
            })
            ->select([
                'twill_users.id as id',
                'twill_users.email as email',
                'companies.title as company',
                'job_roles.title as jobrole',
                'branches.title as branch',
                'departments.title as department',
                DB::raw("
                    COALESCE(
                        NULLIF(
                            TRIM(
                                CONCAT(
                                    COALESCE(twill_users.first_name, ''),
                                    ' ',
                                    COALESCE(twill_users.last_name, '')
                                )
                            ),
                            ''
                        ),
                        'N/A'
                    ) as name
                "),
                DB::raw("DATE_FORMAT(sn.created_at, '%Y-%m-%d %H:%i:%s') as downloaded_at"),
                DB::raw("CASE WHEN sn.created_at IS NOT NULL THEN 'Downloaded' ELSE 'Yet to Download' END as download_status"),
                DB::raw("CONCAT(ROUND(twill_users.average_score * 100, 1), '%') as average_score"),
            ])
            ->orderBy('sn.created_at', 'DESC');
    }

    // Use existing SerialNumber model for inserts and updates
    public function createSerialNumber(array $data)
    {
        return $this->serialNumber->create($data);
    }

    public function updateSerialNumber(int $id, array $data)
    {
        return $this->serialNumber->where('id', $id)->update($data);
    }

    public function findByUserAndCourse(int $userId, ?string $courseId, int $type)
    {
        return $this->serialNumber
            ->where('user_id', $userId)
            ->where('certificate_type', $type)
            ->when($courseId, function ($q) use ($courseId) {
                $q->where('course_id', $courseId); 
            })
            ->first();
    }


    public function filter(Builder $query, array $scopes = []): Builder
    {
        // Remove empty string keys and reserved keys
        foreach (array_keys($scopes) as $key) {
            if ($key === '' || $key === 'status') {
                unset($scopes[$key]);
            }
        }

        // Read downloaded_at directly from request (scopes key is corrupted upstream)
        $filterParam = json_decode(request()->get('filter'), true) ?? [];

        if (!empty($filterParam['downloaded_at'])) {
            $range = explode(' - ', $filterParam['downloaded_at']);

            if (count($range) == 2) {
                $start = Carbon::parse(trim($range[0]))->startOfDay();
                $end = Carbon::parse(trim($range[1]))->endOfDay();

                $query->whereExists(function ($sub) use ($start, $end) {
                    $sub->select(DB::raw(1))
                        ->from('serial_numbers as sn')
                        ->whereColumn('sn.user_id', 'twill_users.id')
                        ->where('sn.certificate_type', 1)
                        ->whereBetween('sn.created_at', [$start, $end]);
                });
            }
        }
            // Handle download_status — unset so parent never sees it
            if (isset($scopes['download_status'])) {
                if ($scopes['download_status'] !== 'all') {
                    $query = $scopes['download_status'] == 1
                        ? $query->whereNotNull('sn.created_at')
                        : $query->whereNull('sn.created_at');
                }
                unset($scopes['download_status']);
            }

            return parent::filter($query, $scopes);
    }


}
