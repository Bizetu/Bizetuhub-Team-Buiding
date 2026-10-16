@twillRepeaterTitle('Event')
@twillRepeaterMax(100)

@formColumns
    @slot('left')
        @formField('input', [
            'name'     => 'event_title',
            'label'    => 'Event Title',
            'minDate'  => '2017-09-10 12:00',
            'required' => true,
        ])
    @endslot
    @slot('right')
        @formField('input', [
            'name'     => 'event_short_description',
            'label'    => 'Event Short Description',
            'required' => false,
        ])
    @endslot
@endformColumns

@formField('medias', [
    'name'  => 'event_cover_image',
    'label' => 'Cover Image',
    'note'  => 'Recommended size: 1200 x 630px',
    'required' => true,
])

@formField('input', [
    'name' => 'event_location',
    'label' => 'Event Location',
    'placeholder' => 'e.g, Kigali, Rwanda',
    'required' => true,
])

@formColumns
    @slot('left')
        @formField('select', [
            'name'    => 'event_publish_status',
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

@formField('wysiwyg', [
    'name'          => 'event_more_info',
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

@formField('repeater', [
    'type' => 'resource_slideshow_repeater',
    'max' => 5,
    'buttonAsLink' => true
])