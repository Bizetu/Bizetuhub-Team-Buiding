<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseGrading extends Model
{
    //
    protected $fillable = [
        'course_id',
        'assignment_type',
        'weight'
    ];

}
