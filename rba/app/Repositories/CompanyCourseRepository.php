<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\CompanyCourse;

class CompanyCourseRepository extends ModuleRepository
{
    use HandleBlocks;

    public function __construct(CompanyCourse $model)
    {
        $this->model = $model;
    }
}
