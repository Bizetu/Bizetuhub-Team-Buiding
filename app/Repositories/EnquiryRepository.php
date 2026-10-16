<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\Enquiry;

class EnquiryRepository extends ModuleRepository
{
    public function __construct(Enquiry $model)
    {
        $this->model = $model;
    }
}
