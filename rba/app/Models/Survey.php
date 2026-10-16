<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Survey extends Model implements Sortable
{
    use HasBlocks, HasRevisions, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'description',
        'position',
        'company_id',
        'job_role_id',
        'department_id',
        'branch_id',
        'publish_start_date',
        'publish_end_date',
    ];

    protected $casts = [
        'publish_start_date' => 'datetime',
        'publish_end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getCompanyTitleAttribute()
    {
        return $this->company ? $this->company->title : '-';
    }

    public function getJobRoleTitleAttribute()
    {
        return $this->jobRole ? $this->jobRole->title : '-';
    }

    public function getDepartmentTitleAttribute()
    {
        return $this->department ? $this->department->title : '-';
    }

    public function getBranchTitleAttribute()
    {
        return $this->branch ? $this->branch->title : '-';
    }
    
}
