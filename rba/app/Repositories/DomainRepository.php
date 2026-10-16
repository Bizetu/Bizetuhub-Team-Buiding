<?php

namespace App\Repositories;


use A17\Twill\Repositories\ModuleRepository;
use App\Models\Domain;

class DomainRepository extends ModuleRepository
{
    

    public function __construct(Domain $model)
    {
        $this->model = $model;
    }
}
