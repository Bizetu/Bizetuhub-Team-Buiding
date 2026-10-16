<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\ServiceCategory;

class ServiceCategoryRepository extends ModuleRepository
{
    public function __construct(ServiceCategory $model)
    {
        $this->model = $model;
    }
}
