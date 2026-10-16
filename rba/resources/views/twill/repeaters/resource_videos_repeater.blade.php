@twillRepeaterTitle('Video')

@formField('input', [
    'name' => 'title',
    'label' => 'Title',
    'placeholder' => 'eg. RBA Reasearch Conference 2025',
    'required' => true,
])

@formField('medias', [
    'name'  => 'resource_video_cover_image',
    'label' => 'Cover Image',
    'note'  => 'Recommended size: 1200 x 630px',
    'required' => true,
])

@formField('radios', [
    'name' => 'video_type',
    'label' => 'Video Type',
    'min' => 1,
    'max' => 3,
    'inline' => true,
    'options' => [
        ['value' => 'mp4', 'label' => 'Mp4'],
        ['value' => 'youtube', 'label' => 'Youtube']
    ]
])
 
@formConnectedFields([
    'fieldName' => 'video_type',
    'fieldValues' => ['mp4'],
    'arrayContains' => true,
    'keepAlive' => true,
    'renderForBlocks' => true
])
    @formField('files', [
        'name' => 'resource_mp4_video',
        'label' => 'Mp4 Video'
    ])
@endformConnectedFields

@formConnectedFields([
    'fieldName' => 'video_type',
    'fieldValues' => ['youtube'],
    'arrayContains' => true,
    'keepAlive' => true,
    'renderForBlocks' => true
])
    @formField('input', [
        'name' => 'youtube_video',
        'label' => 'Title',
        'placeholder'    => 'eg. https://youtube.com/example',
        'required' => true,
    ])
@endformConnectedFields

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