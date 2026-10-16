<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use A17\Twill\Models\Model;
use Carbon\Carbon;

class CompletionReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'published',
        'user_id',
        'username',
        'email',
        'company_id',
        'company',
        'department_id',
        'department',
        'job_role_id',
        'job_role',
        'branch_id',
        'branch',
        'course_id',
        'course',
        'employee_number',
        'country_id',
        'score',
        'max_score',
        'score',
        'max_score',
        'completion_status',
        'enrollment_date',
        'completion_date',
        'user_license_id',
        'effort_hours',
    ];

    protected $casts = [
        'score'  => 'float',
        'enrollment_date' => 'datetime',
        'completion_date' => 'datetime',
    ];

    public function userLicenses()
    {
        return $this->belongsTo(UserLicense::class, 'user_license_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function courseRelationship()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }

    // -------------------------------------------------------------------------
    // Accessor attributes
    // -------------------------------------------------------------------------

    public function getCourseTitleValueAttribute()
    {
        return $this->courseRelationship ? $this->courseRelationship->display_name : '';
    }
    public function getCompanyTitleValueAttribute()
    {
        return $this->company ? $this->company->title : '-';
    }
    public function getBranchTitleValueAttribute()
    {
        return $this->branch ? $this->branch->title : '-';
    }
    public function getDepartmentTitleValueAttribute()
    {
        return $this->department ? $this->department->title : '-';
    }
    public function getJobRoleTitleValueAttribute()
    {
        return $this->jobRole ? $this->jobRole->title : '-';
    }
    public function getDateCreatedAttribute()
    {
        return $this->created_at->format('d-m-Y');
	}
    public function getDateEnrolledAttribute()
    {
        return Carbon::parse ($this->enrollment_date)->format('Y-m-d');
	}
    public function getDateCompletedAttribute()
    {
        return Carbon::parse ($this->completion_date)->format('Y-m-d');
	}
    public function getFormattedScoreAttribute()
    {
        return number_format($this->score * 100, 2) . '%';
	}
    public function getCourseEffortAttribute()
    {
        $effort = $this->courseRelationship ? $this->courseRelationship->effort : null;
        if (!$effort) return null;

        $parts = explode(':', $effort);
        if (count($parts) !== 3) return null;

        $hours = (int)$parts[1] + ((int)$parts[2] / 60);
        if(round($hours, 2) == 1){
            return round($hours, 2) . 'hr';
        } else {
            return round($hours, 2) . 'hrs';
        }
    }

    public function getScoreValueAttribute()
    {
        if ($this->max_score && $this->score !== null && $this->max_score !== 0) {
            return round(($this->score / $this->max_score) * 100, 2);
        }
        return null;
    }
}
