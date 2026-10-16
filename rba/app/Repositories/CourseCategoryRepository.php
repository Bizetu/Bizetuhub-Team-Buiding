<?php

namespace App\Repositories;


use A17\Twill\Repositories\ModuleRepository;
use App\Models\CourseCategory;

class CourseCategoryRepository extends ModuleRepository
{
    

    public function __construct(CourseCategory $model)
    {
        $this->model = $model;
    }
}
