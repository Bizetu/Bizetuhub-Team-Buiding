<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\ContactInterest;

class EnquiryController extends BaseModuleController
{
    protected $moduleName = 'enquiries';

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('name')->label('Contact name'));
        $form->add(Input::make()->name('email')->label('Email'));
        $form->add(Input::make()->name('organisation')->label('Organisation'));
        $form->add(Input::make()->name('phone')->label('Phone'));
        $form->add(Select::make()->name('interest')->label("I'm interested in")->options(
            ContactInterest::withTrashed()->orderBy('position')->orderBy('title')->pluck('title', 'title')->all()
        ));
        $form->add(Wysiwyg::make()->name('message')->label('Message'));
        $form->add(Select::make()->name('status')->label('Enquiry status')->options([
            'new' => 'New',
            'under_review' => 'Under Review',
            'completed' => 'Completed',
        ]));
        $form->add(Wysiwyg::make()->name('internal_notes')->label('Internal notes'));

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('name')->title('Contact name'));
        $table->add(Text::make()->field('email')->title('Email'));
        $table->add(Text::make()->field('interest')->title('Interest'));
        $table->add(Text::make()->field('status')->title('Status'));

        return $table;
    }
}
