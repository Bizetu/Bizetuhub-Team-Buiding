<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use Illuminate\Support\Arr;

class Message extends Model implements Sortable
{
    use HasBlocks, HasRevisions, HasPosition;

    public const MESSAGE_TYPES = [
        '1' => 'Department',
        '2' => 'Course Messages',
    ];

    public const RECURRENT_FREQUENCY = [
        '7' => 'One Week',
        '14' => 'Two Weeks',
        '21' => 'Three Weeks',
        '28' => 'Four Weeks',
    ];

    protected $fillable = [
        'published',
        'title',
        'company_id',
        'message_type',
        'user_ids',
        'target_admins',
        'job_role_ids',
        'department_ids',
        'branch_ids',
        'audience_ids',
        'course_ids',
        'branch_ids',
        'is_recurrent',
        'recurrent_frequency',
        'send_date',
        'sent_status',
        'next_run',
        'position',
    ];

    protected $casts = [
        'user_ids' => 'array',
        'target_admins' => 'array',
        'job_role_ids' => 'array',
        'department_ids' => 'array',
        'branch_ids' => 'array',
        'audience_ids' => 'array',
        'course_ids' => 'array',
        'is_recurrent' => 'boolean',
        'sent_status' => 'boolean',
        'send_date' => 'datetime',
        'next_run' => 'datetime',
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function getCompanyValueAttribute(){
        return $this->company ? $this->company->title : 'Industrywide';
    }

    public function getSentValueAttribute(){
        return $this->sent_status ? 'Sent' : 'Not Sent';
    }

    public function getMessageTypeValueAttribute(){     
        return $this->message_type ? self::MESSAGE_TYPES[$this->message_type] : null;
    }

    public function getIsRecurrentValueAttribute(){     
        return $this->is_recurrent ? 'Recurrent' : 'One Time';
    }

    public function getFrequencyValueAttribute(){     
        return $this->recurrent_frequency ? self::RECURRENT_FREQUENCY[$this->recurrent_frequency] : null;
    }

    public function getSendDateValueAttribute(){
        if($this->is_recurrent && $this->next_run){
            return $this->next_run;
        }
        else{
            return $this->send_date;
        }

    }

    public function getAudienceValueAttribute(){
        return Arr::first($this->audience_ids) ?? ''; 
    }

}
