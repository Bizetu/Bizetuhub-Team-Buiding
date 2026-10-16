<?php

namespace App\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLicense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'published',
        'user_id',
        'course_id',
        'email',
        'username',
        'company_id',
        'job_role_id',
        'branch_id',
        'department_id',
        'expired_at',
        'position',
    ];

    protected $casts = [
        'published'   => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'expired_at'  => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function completion()
    {
        return $this->hasOne(CompletionReport::class, 'user_license_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class, 'job_role_id', 'id');
    }

    /**
     * Get all comments on this license (for training feed)
     */
    public function comments()
    {
        return $this->hasMany(TrainingComment::class, 'license_id')
            ->whereNull('parent_comment_id')
            ->with('replies')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all likes on this license (for training feed)
     */
    public function likes()
    {
        return $this->hasMany(TrainingLike::class, 'like_id');
    }

    // -------------------------------------------------------------------------
    // Accessor attributes (Existing)
    // -------------------------------------------------------------------------

    public function getCourseTitleValueAttribute()
    {
        return $this->course ? $this->course->display_name : '';
    }

    public function getEvaluationStatusAttribute()
    {
        return UserEvaluation::where('user_id', Auth::id())
            ->where('course_id', $this->course_id)
            ->exists();
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

    public function getDateCompletedAttribute()
    {
        $score = $this->score;
        $date = $this->completion?->completion_date;

        if ($score > 79 && $date) {
            return \Carbon\Carbon::parse($date)->format('d-m-Y');
        }

        return 'In Progress';
	}

    public function getCourseStartDateAttribute()
    {
        return $this->course->created_at->format('jS M Y');
	}

    public function getStatusAttribute()
    {
        $score = $this->score;

        return match(true) {
            $score > 79   => 'Completed',
            $score >= 20  => 'Failed',
            default       => 'In Progress',
        };
	}

    public function getScoreAttribute()
    {
        $score = $this->completion?->score;

        return round($score*100, 0);
	}

    public function getActionAttribute()
    {
        $action = $this->course->course_id;

        return $action;
	}

    public function getCourseTitleAttribute()
    {
        $action = $this->course?->display_name;

        return $action;
	}

    public function getTotalEnrolledAttribute()
    {
        $total = UserLicense::where('course_id', $this->course_id)->count();

        return $total;
	}

    public function getTotalCompletedAttribute()
    {
        $total = CompletionReport::where('course_id', $this->course_id)->where('score', '>=', 0.8)->count();

        return $total;
	}

    // -------------------------------------------------------------------------
    // Training Feed Attributes (New)
    // -------------------------------------------------------------------------

    /**
     * Get comment count for this license
     */
    public function getCommentCountAttribute(): int
    {
        return TrainingComment::where('license_id', $this->id)
            ->where('course_id', $this->course_id)
            ->count();
    }

    /**
     * Get like count for this license
     */
    public function getLikeCountAttribute(): int
    {
        return TrainingLike::where('like_id', $this->id)
            ->where('course_id', $this->course_id)
            ->count();
    }

    /**
     * Get users who liked this license
     */
    public function getLikedByUsersAttribute()
    {
        return User::whereIn('id', function($query) {
            $query->select('user_id')
                ->from('training_likes')
                ->where('like_id', $this->id)
                ->where('course_id', $this->course_id);
        })->get();
    }

    /**
     * Check if a specific user has liked this license
     */
    public function isLikedByUser(int $userId): bool
    {
        return TrainingLike::where('like_id', $this->id)
            ->where('course_id', $this->course_id)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get training status for training feed
     */
    public function getTrainingStatusForFeedAttribute(): string
    {
        $score = $this->score;

        return match(true) {
            $score > 79   => 'Completed',
            $score >= 20  => 'Failed',
            default       => 'In Progress',
        };
    }

    /**
     * Check if training is completed with passing grade
     */
    public function isCompletedWithPassingGrade(float $passMark = 80): bool
    {
        return $this->score >= $passMark;
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePublished($query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expired_at')
              ->orWhere('expired_at', '>', now());
        });
    }
}
