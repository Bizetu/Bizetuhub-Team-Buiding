<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleNesting;
use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Models\Contracts\TwillModelContract;
use App\Models\Evaluation;
use App\Models\User;
// use App\Notifications\EvaluationPublished;
use Illuminate\Support\Facades\Notification;

class EvaluationRepository extends ModuleRepository
{
    use HandleBlocks, HandleRevisions, HandleNesting;

    public function __construct(Evaluation $model)
    {
        $this->model = $model;
    }

    public function afterSave(TwillModelContract $model, array $fields): void
    {
        parent::afterSave($model, $fields);

        // if ($model->published && $model->wasChanged('published')) {
        //     $users = User::whereIn('role_id', [1])->get();
        //     Notification::send($users, new EvaluationPublished($model));
        // }
    }
}