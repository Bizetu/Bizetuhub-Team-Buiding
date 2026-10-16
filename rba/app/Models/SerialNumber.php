<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class SerialNumber extends Model implements Sortable
{
    use HasBlocks, HasPosition;

    protected $fillable = [
        'published',
        'user_id',
        'email',
        'certificate_type',
        'course_id',
        'serial_number',
    ];
    

    public function getDownloadStatusAttribute(){
        return $this->created_at ? 'Downloaded' : 'Yet to Download';
    }
}
