@twillRepeaterTitle('External Link')

@formField('input', [
    'name'     => 'title',
    'label'    => 'Title',
    'placeholder'    => 'eg. World Bank - Sustainable Finance',
    'required' => true,
])

@formField('input', [
    'name'     => 'external_url',
    'label'    => 'External URL',
    'placeholder'    => 'eg. https://example.com',
    'required' => true,
])

@formField('medias', [
    'name'  => 'external_link_cover_image',
    'label' => 'Cover Image',
    'note'  => 'Recommended size: 340 x 80px or a square',
    'required' => true,
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