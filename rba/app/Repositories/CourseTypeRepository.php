<?php

namespace App\Repositories;


use A17\Twill\Repositories\ModuleRepository;
use App\Models\CourseType;

class CourseTypeRepository extends ModuleRepository
{
    

    public function __construct(CourseType $model)
    {
        $this->model = $model;
    }
}
