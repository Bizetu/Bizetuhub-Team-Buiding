<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use App\Models\Service;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class BookingController extends BaseModuleController
{
    protected $moduleName = 'bookings';

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);
        $form->add(Input::make()->name('organisation')->label('Organisation'));
        $form->add(Input::make()->name('name')->label('Contact name'));
        $form->add(Input::make()->name('email')->label('Email'));
        $form->add(Input::make()->name('phone')->label('Phone'));
        $form->add(Select::make()
            ->name('service_id')
            ->label('Requested service')
            ->options(Service::orderBy('title')->pluck('title', 'id')->toArray())
        );
        $form->add(Input::make()->name('event_id')->label('Event ID'));
        $form->add(Input::make()->name('preferred_date')->label('Preferred date'));
        $form->add(Input::make()->name('participants')->label('Participants')->type('number'));
        $form->add(Input::make()->name('venue_preference')->label('Venue preference'));
        $form->add(Wysiwyg::make()->name('requirements')->label('Requirements'));
        $form->add(Select::make()->name('status')->label('Booking status')->options([
            'new' => 'New',
            'under_review' => 'Under Review',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ]));
        $form->add(Wysiwyg::make()->name('internal_notes')->label('Internal notes'));

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();
        $table->add(Text::make()->field('organisation')->title('Organisation'));
        $table->add(Text::make()->field('name')->title('Contact name'));
        $table->add(Text::make()->field('email')->title('Email'));
        $table->add(Text::make()->field('service')->title('Programme'));
        $table->add(Text::make()->field('preferred_date')->title('Preferred date'));
        $table->add(Text::make()->field('participants')->title('Participants'));
        $table->add(Text::make()->field('status')->title('Status'));

        return $table;
    }
}
