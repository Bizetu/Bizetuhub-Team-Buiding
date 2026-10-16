<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Models\User as TwillUser;

class User extends TwillUser implements TwillModelContract
{
    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;
    const GENDER_UNDEFINED = 3;
    const PASS_MARK = 70;

    const AUTHENTICATION_GOOGLE = 1;
    const AUTHENTICATION_MICROSOFT = 2;


    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public $timestamps = true;

    protected $casts = [
        'is_superadmin' => 'boolean',
        'is_admin' => 'boolean',
        'is_company_admin' => 'boolean',
        'published' => 'boolean',
        'deleted_at' => 'datetime',
        'registered_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token', 'google_2fa_secret'];

    public $checkboxes = ['published'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('twill.users_table', 'twill_users');

        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'username',
        'last_login_at',
        'registered_at',
        'require_new_password',
        'is_superadmin',
        'is_admin',
        'is_company_admin',
        'first_name',
        'last_name',
        'google_id',
        'microsoft_id',
        'published',
        'authentication_type',
        'email_verified_at',
        'company_initials',
        'user_number',
        'country_id',
        'company_id',
        'company_name',
        'department_id',
        'department_name',
        'branch_id',
        'branch_name',
        'job_role_id',
        'job_role',
        'phone_number',
        'gender_id',
        'profile_pic',
        'initial_profile',
        'role_id',
        'google_2fa_enabled',
        'google_2fa_secret',
        'language',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public static function getGender()
    {
        $types = array(
            array(
                'id' => self::GENDER_FEMALE,
                'title'=>'Female'
            ),
            array(
                'id' => self::GENDER_MALE,
                'title'=>'Male'
            ),
            array(
                'id' => self::GENDER_UNDEFINED,
                'title'=>'Undefined'
            ),
        );
        return $types;
    }
    public function getGenderValueAttribute()
    {
        if(!empty($this->gender_id)) {
            if($this->gender_id == 1) {
                return 'Female';
            } elseif($this->gender_id == 2) {
                return 'Male';
            } else {
                return 'Undefined';
            }
        } else {
            return null;
        }
    }
    // Relationships go here
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }
    public function completions()
    {
        return $this->hasMany(CompletionReport::class);
    }
    public function licenses()
    {
        return $this->hasMany(UserLicense::class);
    }

    public function followers()
    {
        return $this->hasMany(UserFollow::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(UserFollow::class, 'follower_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(UserMessage::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(UserMessage::class, 'receiver_id');
    }

    // Attributes go here
    public function setFirstNameAttribute(string $value): void
    {
        $this->attributes['first_name'] = $value;

        if (!empty($this->last_name)) {
            $this->attributes['name'] = "{$value} {$this->last_name}";
        }
    }
    public function setLastNameAttribute(string $value): void
    {
        $this->attributes['last_name'] = $value;

        if (!empty($this->first_name)) {
            $this->attributes['name'] = "{$this->first_name} {$value}";
        }
    }
    public function getNameValueAttribute(): string
    {
        return $this->name ?? (($this->first_name && $this->last_name) ? "{$this->first_name} {$this->last_name}" : '-');
    }
    public function getCompanyNameValueAttribute()
    {
        return $this->company ? $this->company->title : '-';
    }
    public function getDepartmentNameValueAttribute()
    {
        return $this->department ? $this->department->title : '-';
    }
    public function getBranchNameValueAttribute()
    {
        return $this->branch ? $this->branch->title : '-';
    }
    public function getJobRoleNameValueAttribute()
    {
        return $this->jobRole ? $this->jobRole->title : '-';
    }
    public function getDateCreatedAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }
    public function getLastLoginAtValueAttribute()
    {
        return $this->last_login_at ? $this->last_login_at->diffForHumans() : '-';
    }
    public function getProfilePicUrlAttribute(): string
    {
        if ($this->profile_pic && Storage::disk('public')->exists($this->profile_pic)) {
            return Storage::disk('public')->url($this->profile_pic);
        }
        return asset('images/defaults/avatar.webp');
    }
    public function getProfilePicAvatarAttribute(): string
    {
        if ($this->profile_pic && Storage::disk('public')->exists($this->profile_pic)) {
            return Storage::disk('public')->url($this->profile_pic);
        }
        return asset('images/defaults/navy-bg-avatar.svg');
    }
    /**
     * Check if user is certified
     *
     * Requirements: Must have completed all the eight published modules provided on the platform
     *
     * @return bool
     */
    public function getCertifiedAttribute(): bool
    {
        $mandatoryCourseIds = Course::published()->pluck('course_id')->toArray();
        if (empty($mandatoryCourseIds)) {
            return false;
        }
        $completion = DB::table('completion_reports')
            ->where('user_id', $this->id)
            ->whereIn('course_id', $mandatoryCourseIds)
            ->selectRaw('
                COUNT(DISTINCT course_id) as completed_count,
                COALESCE(SUM(score), 0) as total_score,
                COALESCE(SUM(max_score), 0) as total_max_score
            ')
            ->first();

        if ($completion->completed_count !== count($mandatoryCourseIds)) {
            return false;
        }

        if ($completion->total_max_score == 0) {
            return false;
        }

        $averagePercentage = ($completion->total_score / $completion->total_max_score) * 100;

        return $averagePercentage >= self::PASS_MARK;
    }

    /**
     * Get certified user IDs efficiently
     *
     * @param int|null $companyId
     * @return array
     */
    public static function getCertifiedUserIds(?int $companyId = null): array
    {
        $query = static::published()->with(['completions']);

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return $query->get()->filter(fn($user) => $user->certified)->pluck('id')->toArray();
    }

    /**
     * Scope to get only certified users
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCertified($query)
    {
        return $query->published()->with(['completions'])->get()->filter(fn($user) => $user->certified);
    }

    /**
     * Scope to filter by company
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $companyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function getIsHrAttribute(): bool
    {
        $user = auth()->guard('twill_users')->user();

        if (!$user || !$user->role_id) {
            return false;
        }

        return ($user->role_id === 3 || $user->is_company_admin === 1 || $user->role_value === 'Company HR');
    }

    /**
     * Get unread messages count
     *
     * @return int
     */
    public function getUnreadMessagesCountAttribute(): int
    {
        return $this->receivedMessages()->unread()->count();
    }

    /**
     * Get pending follow requests count
     *
     * @return int
     */
    public function getPendingFollowRequestsCountAttribute(): int
    {
        return $this->followers()->pending()->count();
    }

    public function getIsGoogleUserAttribute()
    {
        return $this->authentication_type === "1";
    }
}
