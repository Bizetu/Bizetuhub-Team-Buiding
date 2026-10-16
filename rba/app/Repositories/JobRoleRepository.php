<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\JobRole;

class JobRoleRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(JobRole $model)
    {
        $this->model = $model;
    }
}
