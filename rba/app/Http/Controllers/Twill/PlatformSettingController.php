<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Color;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Files;

class PlatformSettingController extends BaseModuleController
{
    protected $moduleName = 'platformSettings';
    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
    }

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('position')->label('Position')
        );

        $wysiwygOptions = [
            ['header' => [2, 3, 4, 5, 6, false]],
            'bold',
            'italic',
            'underline',
            'strike',
            'blockquote',
            'code-block',
            'ordered',
            'bullet',
            'hr',
            'code',
            'link',
            'clean',
            'table',
            'align',
        ];

        $form->add(
            Wysiwyg::make()
                ->name('tag_line')
                ->toolbarOptions($wysiwygOptions)
                ->allowSource(true)
                ->maxLength(200)
                ->note('Tag Line')
        );

        $form->add(
            Medias::make()->name('logo')->label('Company Logo')->note('Site Logo')
        );

        $form->add(
            Files::make()->name('learn_navigate')->label('Learn to Navigate this site Video')
        );

        $form->add(
            Color::make()->name('primary_color')->label('Primary Color')
        );

        $form->add(
            Color::make()->name('secondary_color')->label('Secondary Color')
        );

        $form->add(
            Color::make()->name('third_color')->label('Third Color')
        );

        $form->add(
            Color::make()->name('menu_color')->label('Menu Color')
        );

        $form->add(
            Color::make()->name('menu_color_active')->label('Menu Color Active')
        );

        $form->add(
            Color::make()->name('footer_color')->label('Footer Color')
        );

        $form->add(
            Color::make()->name('enroll_color')->label('Enroll Color')
        );

        $form->add(
            Color::make()->name('progress_color')->label('Progress Color')
        );

        $form->add(
            Color::make()->name('retake_color')->label('Retake Color')
        );

        $form->add(
            Color::make()->name('completed_color')->label('Completed Color')
        );

        $form->add(
            Medias::make()->name('registration_background')->label('Registration Background')->note('Image on the left of the registration page')
        );

        $form->add(
            Medias::make()->name('login_background')->label('Login Background')->note('Recommended: 500 x 700px')
        );

        $form->add(
            Medias::make()->name('mandatory_svg')->label('Mandatory svg')->note('Mandatory svg')
        );

        $form->add(
            Medias::make()->name('hr_recommended_svg')->label('HR Recommended svg')->note('HR Recommended svg')
        );

        $form->add(
            Medias::make()->name('clock_svg')->label('Clock svg')->note('Clock svg')
        );
        $form->add(
            Medias::make()->name('calendar_svg')->label('Calendar svg')->note('Calendar svg')
        );
        $form->add(
            Medias::make()->name('tick_svg')->label('Tick svg')->note('Tick svg')
        );
        $form->add(
            Medias::make()->name('user_svg')->label('User svg')->note('User svg')
        );
        $form->add(
            Medias::make()->name('notification_bell_svg')->label('Notification Bell svg')->note('Notification Bell svg')
        );
        $form->add(
            Medias::make()->name('rocket_svg')->label('Rocket svg')->note('Rocket svg')
        );
        $form->add(
            Medias::make()->name('not_finished_trophy_svg')->label('Not finished Trophy svg')->note('Not finished Trophy svg')
        );
        $form->add(
            Medias::make()->name('finished_trophy_svg')->label('Finished Trophy svg')->note('Finished Trophy svg')
        );
        $form->add(
            Medias::make()->name('like_svg')->label('Like svg')->note('Like svg')
        );
        $form->add(
            Medias::make()->name('comment_svg')->label('Comment svg')->note('Comment svg')
        );

        return $form;
    }
}
