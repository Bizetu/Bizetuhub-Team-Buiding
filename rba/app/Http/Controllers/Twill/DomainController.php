<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

class DomainController extends BaseModuleController
{
    protected $moduleName = 'domains';

    protected $defaultOrders = [
        'name' => 'asc',
    ];

    protected $defaultIndexOptions = [
        'create' => false,
        'edit' => false,
        'publish' => false,
        'bulkPublish' => false,
        'feature' => false,
        'bulkFeature' => false,
        'restore' => false,
        'bulkRestore' => false,
        'forceDelete' => false,
        'bulkForceDelete' => false,
        'delete' => false,
        'duplicate' => false,
        'bulkDelete' => false,
        'reorder' => false,
        'permalink' => false,
        'bulkEdit' => false,
        'editInModal' => false,
        'skipCreateModal' => false,
        'includeScheduledInList' => false,
        'showImage' => false,
        'sortable' => false,
    ];

    protected $indexColumns = [
        'title' => [
            'title' => 'Name',
            'field' => 'name',
            'sort' => true,
        ],
        'company_value' => [
            'title' => 'Company',
            'field' => 'company_value',
            'sort' => true,
            'sortKey' => 'company_id',
        ],
        'created_at' => [
            'title' => 'Created On',
            'field' => 'created_at',
        ],
        'updated_at' => [
            'title' => 'Updated On',
            'field' => 'updated_at',
        ],
    ];

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        return $form;
    }
}
