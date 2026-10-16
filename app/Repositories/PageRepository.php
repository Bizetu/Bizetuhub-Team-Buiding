<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Page;

class PageRepository extends ModuleRepository
{
    use HandleTranslations, HandleBlocks, HandleSlugs, HandleMedias;

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve one of the fixed website pages by its configured page key.
     *
     * This mirrors the RBA page repository contract while keeping the
     * team-building application's page_key field and translations intact.
     */
    public function getPage(string $key, array $with = []): ?Page
    {
        return $this->getByKey($key, $with);
    }

    public function getByKey(string $key, array $with = []): ?Page
    {
        return $this->model
            ->with($with)
            ->where('page_key', $key)
            ->first();
    }
}
