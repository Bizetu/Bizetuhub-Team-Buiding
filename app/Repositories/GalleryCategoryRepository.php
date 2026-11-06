<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\GalleryCategory;

class GalleryCategoryRepository extends ModuleRepository
{
    public function __construct(GalleryCategory $model)
    {
        $this->model = $model;
    }
}
