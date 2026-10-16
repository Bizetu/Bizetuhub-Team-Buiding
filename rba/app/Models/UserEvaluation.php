<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use A17\Twill\Models\Model;

class UserEvaluation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'published',
        'course_id',
        'username',
        'user_id',
        'company_id',
        'question1', 'answer1',
        'question2', 'answer2',
        'question3', 'answer3',
        'question4', 'answer4',
        'question5', 'answer5',
        'question6', 'answer6',
        'question7', 'answer7',
        'question8', 'answer8',
        'question9', 'answer9',
        'question10', 'answer10',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getCompanyNameValueAttribute()
    {
        return $this->company ? $this->company->name : '-';
    }

    public function getDateCreatedAttribute()
    {
        return $this->created_at->format('d-m-Y');
	}

    public function getDateUpdatedAttribute()
    {
        return $this->updated_at->format('d-m-Y');
	}

    public function getCourseTitleValueAttribute()
    {
        if($this->course){
            return $this->course->display_name;
        } else {
            return '-';
        }
    }
}
