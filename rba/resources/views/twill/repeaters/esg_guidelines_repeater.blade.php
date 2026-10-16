@twillRepeaterTitle('ESG Guideline')

@formField('input', [
    'name' => 'title',
    'label' => 'Title',
    'placeholder' => 'eg. Governance',
    'required' => true,
])

@formField('medias', [
    'name'  => 'pillar_image_icon',
    'label' => 'Icon',
    'note'  => 'Recommended size: 1200 x 630px',
    'required' => true,
])

@formField('medias', [
    'name'  => 'bestpractice_image_icon',
    'label' => 'Best Practice Icon',
    'note'  => 'Recommended size: 1200 x 630px',
])
 
@formField('wysiwyg', [
    'name'          => 'pillar_more_info',
    'label'         => 'More Info',
    'editSource'    => true,
    'note'          => 'Read More Text',
    'toolbarOptions' => [
        ['header' => [2, 3, 4, 5, 6, false]],
        'bold', 'italic', 'underline', 'strike',
        ['script' => 'super'],
        ['script' => 'sub'],
        'blockquote', 'code-block',
        ['list' => 'ordered'],
        ['list' => 'bullet'],
        ['indent' => '-1'],
        ['indent' => '+1'],
        ['align' => []],
        ['direction' => 'rtl'],
        'link', 'clean',
    ],
])

@formField('wysiwyg', [
    'name'          => 'best_practice',
    'label'         => 'Best Practise',
    'editSource'    => true,
    'note'          => 'Best Practice Text',
    'toolbarOptions' => [
        ['header' => [2, 3, 4, 5, 6, false]],
        'bold', 'italic', 'underline', 'strike',
        ['script' => 'super'],
        ['script' => 'sub'],
        'blockquote', 'code-block',
        ['list' => 'ordered'],
        ['list' => 'bullet'],
        ['indent' => '-1'],
        ['indent' => '+1'],
        ['align' => []],
        ['direction' => 'rtl'],
        'link', 'clean',
        'table',
    ],
])

@formColumns
    @slot('left')
        @formField('select', [
            'name'    => 'publish_status',
            'label'   => 'Published',
            'required' => true,
            'options' => [
                ['value' => 1, 'label' => 'True'],
                ['value' => 0, 'label' => 'False'],
            ],
        ])
    @endslot
    @slot('right')
        @formField('date_picker', [
            'name'     => 'date_published',
            'label'    => 'Date Published',
            'minDate'  => '2020-01-01 12:00',
            'required' => true,
        ])
    @endslot
@endformColumns