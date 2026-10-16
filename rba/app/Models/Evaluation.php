<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasNesting;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;

class Evaluation extends Model implements Sortable
{
    use HasBlocks, HasRevisions, HasPosition, HasNesting;

    protected $fillable = [
        'published',
        'title',
        'course_id',
        'evaluation_option_id',
        'position',
    ];

    public function evaluationOption()
    {
        return $this->belongsTo(EvaluationOption::class, 'evaluation_option_id');
    }

    public static function getRadio($option, $item)
    {
        $evaluationOptions = EvaluationOption::where('id', $option)->first();

        $values = [
            $evaluationOptions->value1,
            $evaluationOptions->value2,
            $evaluationOptions->value3,
            $evaluationOptions->value4,
        ];

        if (!empty($evaluationOptions->value5)) {
            $values[] = $evaluationOptions->value5;
        }

        $html = '';
        foreach ($values as $value) {
            $html .= '
                <label class="flex items-center p-2 border border-[#1F3B67] rounded-md cursor-pointer transition-all duration-200 hover:border-[#162A4A] hover:bg-gray-50 has-[:checked]:border-[#162A4A] has-[:checked]:bg-gray-50 group">
                    <input 
                        type="radio" 
                        name="' . $item . '" 
                        value="' . $value . '" 
                        style="accent-color: #1F3B67;" 
                        class="w-4 h-4 text-[#1F3B67] border-[#1F3B67] cursor-pointer"
                        required
                    >
                    <span class="ml-3 text-lg font-medium text-[#1F3B67]">' . $value . '</span>
                </label>
            ';
        }

        return $html;
    }

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function getCourseTitleValueAttribute()
    {
        if($this->courses){
            return $this->courses->display_name;
        } else {
            return '-';
        }
    }
}