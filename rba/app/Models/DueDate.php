<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Carbon\Carbon;

class DueDate extends Model implements Sortable
{
    use HasBlocks, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'course_id',
        'company_id',
        'branch_id',
        'department_id',
        'job_role_id',
        'due_date',
        'position',
        'publish_start_date',
    ];

    protected $casts = [
        'due_date'          => 'datetime',
        'publish_start_date' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }

    public function getCompanyNameAttribute(): string
    {
        return $this->company ? $this->company->title : '-';
    }

    public function getBranchNameAttribute(): string
    {
        return $this->branch ? $this->branch->title : '-';
    }

    public function getDepartmentNameAttribute(): string
    {
        return $this->department? $this->department->title : '-';
    }

    public function getJobRoleNameAttribute(): string
    {
        return $this->jobRole ? $this->jobRole->title : '-';
    }

    public function getDateDueAttribute(): string
    {
        return $this->due_date ? $this->due_date->format('d-m-Y') : null;
    }

    public function scopeForUser($query, $user)
    {
        return $query
            ->where(function ($q) use ($user) {
                $q->where('due_dates.company_id', $user->company_id)
                ->orWhereNull('due_dates.company_id');
            })
            ->where(function ($q) use ($user) {
                $q->where('due_dates.branch_id', $user->branch_id)
                ->orWhereNull('due_dates.branch_id');
            })
            ->where(function ($q) use ($user) {
                $q->where('due_dates.department_id', $user->department_id)
                ->orWhereNull('due_dates.department_id');
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('due_dates.job_role_id')
                ->orWhere('due_dates.job_role_id', $user->job_role_id);
            });
    }
}
