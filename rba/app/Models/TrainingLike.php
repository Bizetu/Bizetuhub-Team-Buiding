<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'like_id', // This is the license_id
        'course_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who liked
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the license that was liked
     */
    public function license()
    {
        return $this->belongsTo(UserLicense::class, 'like_id');
    }

    /**
     * Get the course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}