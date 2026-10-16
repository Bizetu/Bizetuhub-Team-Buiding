@twillRepeaterTitle('Slider')

@formField('radios', [
    'name' => 'media_type',
    'label' => 'Media Type',
    'default' => 'image',
    'inline' => true,
    'options' => [
        [
            'value' => 'image',
            'label' => 'Image'
        ],
        [
            'value' => 'video',
            'label' => 'Video'
        ]
    ]
])

@formConnectedFields([
    'fieldName' => 'media_type',
    'fieldValues' => 'image',
    'renderForBlocks' => true,
])
    @formField('medias', [
        'name' => 'slide_image',
        'label' => 'Slide Image (Desktop)',
        'max' => 1,
        'note' => 'Recommended size: 1920x1080px'
    ])

    @formField('medias', [
        'name' => 'mobile_slide_image',
        'label' => 'Slide Image (Mobile)',
        'max' => 1,
        'note' => 'Recommended size: 768x1024px (Optional - desktop image will be used if not provided)'
    ])
@endformConnectedFields

@formConnectedFields([
    'fieldName' => 'media_type',
    'fieldValues' => 'video',
    'renderForBlocks' => true,
])
    @formField('input', [
        'name' => 'video_url',
        'label' => 'YouTube Video URL',
        'note' => 'First url will be picked',
        'placeholder' => 'https://www.youtube.com/watch?v=...'
    ])

    @formField('input', [
        'name' => 'bunny_net_video_url',
        'label' => 'Bunny.net Video URL',
        'note' => 'Full URL (e.g., https://player.mediadelivery.net/embed/...)',
        'placeholder' => 'https://player.mediadelivery.net/embed/...'
    ])

    @formField('medias', [
        'name' => 'video_thumbnail',
        'label' => 'Video Thumbnail (Optional)',
        'max' => 1,
        'note' => 'Fallback image shown before video loads'
    ])
@endformConnectedFields

@formField('input', [
    'name' => 'title',
    'label' => 'Title',
    'maxlength' => 100,
])

@formField('wysiwyg', [
    'name' => 'description',
    'label' => 'Description',
    'maxlength' => 250,
    'toolbarOptions' => [['header' => [2, 3, 4, 5, 6, false]], 'bold', 'italic', 'underline', 'strike'],
])

@formField('input', [
    'name' => 'link_text',
    'label' => 'Button Text',
    'maxlength' => 80,
    'placeholder' => 'e.g., View Courses, Learn More'
])

@formField('input', [
    'name' => 'url',
    'label' => 'Button URL',
    'placeholder' => 'https://example.com',
    'note' => 'Existing route or external URL'
])