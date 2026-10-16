<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Menu extends Model implements Sortable
{
    use HasRevisions, HasPosition;

    protected $fillable = [
        'published',
        'title',
        'position',
        'key',
        'menu_type_id',
    ];

    public function menuType()
    {
        return $this->belongsTo(MenuType::class);
    }

    public function getMenuTypeValueAttribute()
    {
        return $this->menuType->title ?? '-';
    }
    
}
