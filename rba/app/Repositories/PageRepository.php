<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Page;

class PageRepository extends ModuleRepository
{
    use HandleBlocks, HandleSlugs, HandleMedias, HandleFiles, HandleRevisions;

    const PAGE_HOMEPAGE = 'home';
    const PAGE_COURSES = 'courses';
    const PAGE_CASESTUDIES = 'case-studies';
    const PAGE_COURSEDETAILS = 'course-details';

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function getPage($key, $with = [])
    {
        return $this->getByKey($key, $with);
    }

    public function getByKey($key, $with = [])
    {
        if (
            ($page = $this->model->with($with)->where('key', $key)->first()) == null
        ) {
            if (isset($this->defaultTitle[$key])) {
                $title = $this->defaultTitle[$key];
            } else {
                $title = 'Untitled';
            }
        }
        
        return $page;
    }
}
