<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Company extends Model implements Sortable
{
    use HasMedias, HasFiles, HasRevisions, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'contact_first_name',
        'contact_last_name',
        'contact_email',
        'company_code',
        'position',
        'staff_number',
        'authentication_method',
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class)->orderBy('position');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class)->orderBy('title');
    }

    public function getBranchCountAttribute()
    {
        return $this->branches ? count($this->branches) : '-';
    }

    public function getDomainCountAttribute()
    {
        return $this->domains ? count($this->domains) : '-';
    }

    public function  getRegisteredAttribute()
    {
        return $this->users->count();
    }

    public function  getCompletedAttribute()
    {
        return User::where('company_id', $this->id)->where('completion_status', 1)->count();
    }

    public function getPercentageAttribute()
    {
        if ($this->registered <= 0 || $this->completed <= 0) {
            return 0;
        }

        return ($this->completed / $this->registered) * 100 . '%';
    }
}
