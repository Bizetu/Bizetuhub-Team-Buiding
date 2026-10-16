<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Course;
use App\Models\User;
use App\Notifications\CoursePublished;
use A17\Twill\Models\Contracts\TwillModelContract;
use Illuminate\Support\Facades\Notification;

class CourseRepository extends ModuleRepository
{
    use HandleSlugs, HandleMedias, HandleFiles, HandleRevisions;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    public function afterSave(TwillModelContract $model, array $fields): void
    {
        parent::afterSave($model, $fields);

        if ($model->published && $model->wasChanged('published')) {
            // $users = User::whereIn('role_id', [1])->get();
            $users = User::published()->get();
            Notification::send($users, new CoursePublished($model));
        }
    }
}
