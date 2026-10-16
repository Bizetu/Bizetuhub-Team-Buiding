@twillRepeaterTitle('Publication')

@formField('input', [
    'name' => 'title',
    'label' => 'Title',
    'placeholder' => 'eg. High-level Research Framework',
    'required' => true,
])

@formField('medias', [
    'name'  => 'publication_cover_image',
    'label' => 'Cover Image',
    'note'  => 'Recommended size: 1200 x 630px',
    'required' => true,
])

@formField('files', [
    'name'  => 'publication_file',
    'label' => 'Publication File',
    'note'  => 'Recommended size: Not greater than 2MB',
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