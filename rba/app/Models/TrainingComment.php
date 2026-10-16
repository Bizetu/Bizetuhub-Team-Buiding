<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_id',
        'course_id',
        'parent_comment_id',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who made the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the license this comment is on
     */
    public function license()
    {
        return $this->belongsTo(UserLicense::class, 'license_id');
    }

    /**
     * Get the course this comment is on
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the parent comment (for replies)
     */
    public function parentComment()
    {
        return $this->belongsTo(TrainingComment::class, 'parent_comment_id');
    }

    /**
     * Get all replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(TrainingComment::class, 'parent_comment_id')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Scope to get only top-level comments (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_comment_id');
    }

    /**
     * Scope to get only replies (has parent)
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_comment_id');
    }

    /**
     * Check if this is a reply
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_comment_id);
    }
}