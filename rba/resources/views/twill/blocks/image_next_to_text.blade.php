@twillBlockTitle('Image Next to Text')
@twillBlockIcon('image')
@twillBlockGroup('app')

@formField('select', [
    'name'    => 'image_position',
    'label'   => 'Image Side',
    'default' => 'left',
    'options' => [
        ['value' => 'left',  'label' => 'Left'],
        ['value' => 'right', 'label' => 'Right'],
    ],
])

@formField('medias', [
    'name'  => 'image_beside',
    'label' => 'Image',
    'note'  => 'Recommended: 550 × 550 px (square works best)',
])

@formField('input', [
    'name'      => 'image_caption_title',
    'label'     => 'Image Caption Title',
    'maxlength' => 200,
])

@formField('input', [
    'name'      => 'image_caption_subtitle',
    'label'     => 'Image Caption Subtitle',
    'maxlength' => 200,
])

@formField('input', [
    'name'      => 'title',
    'label'     => 'Title',
    'maxlength' => 200,
])

@formField('wysiwyg', [
    'name'           => 'description',
    'label'          => 'Description',
    'maxlength'      => 5000,
    'editSource'     => true,
    'note'  => 'Copy source text, click on source code, then paste directly',
    'toolbarOptions' => [
        ['header' => [2, 3, 4, 5, 6, false]],
        'bold',
        'italic',
        'underline',
        'strike',
        'blockquote',
        'ordered',
        'bullet',
        'hr',
        'link',
        'clean',
    ],
])

@formColumns
    @slot('left')
        @formField('input', [
            'name'        => 'cta_label',
            'label'       => 'Read More — Button Label',
            'placeholder' => 'Read More',
            'maxlength'   => 80,
        ])
    @endslot
    @slot('right')
        @formField('input', [
            'name'      => 'cta_url',
            'label'     => 'Read More — URL',
            'maxlength' => 500,
        ])
    @endslot
@endformColumns