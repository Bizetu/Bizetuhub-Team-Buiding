<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'creator_id',
        'is_active',
        'published',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ForumMember::class);
    }

    public function approvedMembers(): HasMany
    {
        return $this->hasMany(ForumMember::class)->where('status', ForumMember::STATUS_APPROVED);
    }

    public function pendingMembers(): HasMany
    {
        return $this->hasMany(ForumMember::class)->where('status', ForumMember::STATUS_PENDING);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(ForumBlock::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function isCreator(User $user): bool
    {
        return $this->creator_id === $user->id;
    }

    public function isMember(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->where('status', ForumMember::STATUS_APPROVED)
            ->exists();
    }

    public function isBlocked(User $user): bool
    {
        return $this->blocks()->where('user_id', $user->id)->exists();
    }

    public function getMemberStatus(User $user): ?int
    {
        // Blocked takes priority
        if ($this->isBlocked($user)) {
            return null; // Return null for blocked users so they don't see declined status
        }

        $member = $this->members()->withTrashed()->where('user_id', $user->id)->first();
        return $member ? $member->status : null;
    }
}