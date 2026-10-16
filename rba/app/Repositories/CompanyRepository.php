<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Company;

use A17\Twill\Models\Contracts\TwillModelContract;

class CompanyRepository extends ModuleRepository
{
    use HandleMedias, HandleFiles, HandleRevisions;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    public function afterSave(TwillModelContract $object, array $fields): void
    {
    
        $this->updateRepeater($object, $fields, 'domains', 'Domain','domain');
        parent::afterSave($object, $fields);

        if($object->published == 1){
            $object->domains()->update(['published'=>1]);
        }else{
            $object->domains()->update(['published'=>0]);
        }
    }

    public function getFormFields(TwillModelContract $object): array{
        $fields = parent::getFormFields($object);
        $fields = $this->getFormFieldsForRepeater($object, $fields, 'domains','Domain','domain');

        return $fields;
    }

    public function prepareFieldsBeforeCreate(array $fields): array{
        $fields['layout'] ='regular';
        return parent::prepareFieldsBeforeCreate($fields);
    }
}
