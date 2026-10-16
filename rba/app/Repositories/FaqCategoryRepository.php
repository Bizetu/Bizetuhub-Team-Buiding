<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\FaqCategory;

class FaqCategoryRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(FaqCategory $model)
    {
        $this->model = $model;
    }
}
