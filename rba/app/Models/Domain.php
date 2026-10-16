<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Domain extends Model implements Sortable
{
    use HasPosition;

    protected $fillable = [
        'published',
        'name',
        'company_id',
        'position',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getCompanyValueAttribute()
    {
        return $this->company->title ?? '-';
    }
    
}
