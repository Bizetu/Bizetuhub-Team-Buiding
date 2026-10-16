<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\GalleryItem;

class GalleryItemRepository extends ModuleRepository
{
    use HandleMedias;

    public function __construct(GalleryItem $model)
    {
        $this->model = $model;
    }
}
