<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSurvey extends Model implements Sortable
{
    use HasBlocks, HasRevisions, HasPosition, SoftDeletes;

    protected $fillable = [
        'published',
        'title',
        'position',
        'survey_id',
        'user_id',
        'email',
        'name',
        'branch_id',
        'company_id',
        'department_id',
        'job_role_id',
    ];

    protected $casts = [
        'answer_1' => 'array',
        'answer_2' => 'array',
        'answer_3' => 'array',
        'answer_4' => 'array',
        'answer_5' => 'array',
        'answer_6' => 'array',
        'answer_7' => 'array',
        'answer_8' => 'array',
        'answer_9' => 'array',
        'answer_10' => 'array',
        'answer_11' => 'array',
        'answer_12' => 'array',
        'answer_13' => 'array',
        'answer_14' => 'array',
        'answer_15' => 'array',
        'answer_16' => 'array',
        'answer_17' => 'array',
        'answer_18' => 'array',
        'answer_19' => 'array',
        'answer_20' => 'array',
        'answer_21' => 'array',
        'answer_22' => 'array',
        'answer_23' => 'array',
        'answer_24' => 'array',
        'answer_25' => 'array',
        'answer_26' => 'array',
        'answer_27' => 'array',
        'answer_28' => 'array',
        'answer_29' => 'array',
        'answer_30' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }

    public function getSurveyTitleAttribute()
    {
        return $this->survey ? $this->survey->title : null;
    }

    public function getBranchTitleAttribute()
    {
        return $this->branch ? $this->branch->title : null;
    }

    public function getCompanyTitleAttribute()
    {
        return $this->company ? $this->company->title : null;
    }

    public function getDepartmentTitleAttribute()
    {
        return $this->department ? $this->department->title : null;
    }

    public function getJobRoleTitleAttribute()
    {
        return $this->jobRole ? $this->jobRole->title : null;
    }

    public function getDateCreatedAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }
}
