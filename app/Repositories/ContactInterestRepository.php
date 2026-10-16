<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\ContactInterest;

class ContactInterestRepository extends ModuleRepository
{
    public function __construct(ContactInterest $model)
    {
        $this->model = $model;
    }
}
