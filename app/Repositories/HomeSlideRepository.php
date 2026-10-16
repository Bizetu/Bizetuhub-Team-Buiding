<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\HomeSlide;

class HomeSlideRepository extends ModuleRepository
{
    use HandleMedias;
    use HandleFiles;

    public function __construct(HomeSlide $model)
    {
        $this->model = $model;
    }
}
