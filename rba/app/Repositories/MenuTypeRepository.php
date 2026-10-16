<?php

namespace App\Repositories;


use A17\Twill\Repositories\ModuleRepository;
use App\Models\MenuType;

class MenuTypeRepository extends ModuleRepository
{
    

    public function __construct(MenuType $model)
    {
        $this->model = $model;
    }
}
