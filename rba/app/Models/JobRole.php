<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Carbon\Carbon;


class JobRole extends Model implements Sortable
{
    use HasRevisions, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'company_id',
        'department_id',
        'course_id',
        'position',
    ];

    protected $casts = [
        'course_id' => 'array'  
    ];

    public function getCourseIdAttribute($value)
    {
        return collect(json_decode($value))->map(function($item) {
            return ['id' => $item];
        })->all();
    }
    
    public function setCourseIdAttribute($value)
    {
        $this->attributes['course_id'] = collect($value)->filter()->values();
    }
        
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getDepartmentValueAttribute()
    {
        return $this?->department?->title ?? '-';
    }

    public function getCompanyValueAttribute()
    {
        return $this?->company?->title ?? '-';
    }
}
