<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\Color;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\MenuItem;
use App\Models\Page;

class PageController extends BaseModuleController
{
    protected $moduleName = 'pages';

    protected $defaultOrders = [
        'position' => 'asc',
    ];

    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        // Pages follow the same root-level permalink convention as the RBA CMS.
        // Fixed website routes still take precedence over this fallback route.
        $this->setPermalinkBase('');
    }

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $menuOptions = MenuItem::where('published', true)
            ->orderBy('position')
            ->orderBy('title')
            ->pluck('title', 'id')
            ->all();

        $form->add(
            Select::make()
                ->name('menu_item_id')
                ->label('Menu')
                ->options($menuOptions)
                ->clearable()
        );

        $form->add(
            Input::make()
                ->name('position')
                ->label('Position')
                ->type('number')
                ->step(1)
                ->min(0)
        );
        $form->add(Input::make()->name('header_title')->label('Header title')->translatable());
        $form->add(
            Medias::make()
                ->name('hero')
                ->label('Hero image')
                ->max(1)
        );
        $form->add(
            Medias::make()
                ->name('page_background')
                ->label('Page background image')
                ->note('Optional. This image appears behind this page and overrides the global site background.')
                ->max(1)
        );
        $form->add(
            Color::make()
                ->name('page_background_color')
                ->label('Page body background color')
                ->note('Optional. Leave blank to use the global site background color.')
        );

        $form->add(
            Wysiwyg::make()->name('description')->label('Page content')->translatable()
        );

        $blocksByPage = [
            'home' => ['home_intro', 'home_process', 'home_section_heading', 'content_section', 'card_grid', 'call_to_action'],
            'about' => ['mission_vision_belief', 'content_section', 'card_grid', 'call_to_action'],
            'services' => ['content_section', 'card_grid', 'call_to_action'],
            'events' => ['content_section', 'card_grid', 'call_to_action'],
            'industries' => ['content_section', 'card_grid', 'call_to_action'],
            'gallery' => ['content_section', 'card_grid', 'call_to_action'],
            'packages' => ['content_section', 'card_grid', 'call_to_action'],
            'testimonials' => ['content_section', 'card_grid', 'call_to_action'],
            'faqs' => ['faq_list'],
            'privacy' => ['content_section'],
            'terms' => ['content_section'],
            'insights' => ['content_section', 'card_grid', 'call_to_action'],
            'contact' => ['content_section', 'call_to_action'],
            'book' => ['content_section', 'call_to_action'],
        ];

        $form->add(
            BlockEditor::make()->blocks($blocksByPage[$model->page_key] ?? ['content_section'])
        );

        return $form;
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('page_key')->title('Website page')
        );

        $table->add(
            Text::make()->field('position')->title('Position')
        );

        $table->add(
            Text::make()->field('header_title')->title('Header title')
        );

        $table->add(
            Text::make()->field('description')->title('Description')
        );

        return $table;
    }
}
