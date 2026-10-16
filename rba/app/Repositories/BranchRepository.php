<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Branch;

class BranchRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(Branch $model)
    {
        $this->model = $model;
    }
}
