@twillBlockTitle('Rwandas Green Growth')
@twillBlockIcon('image')
@twillBlockGroup('app')

@formField('medias', [
    'name'  => 'rwandas_green_growth_img',
    'label' => 'Image',
    'note'  => 'Recommended: 1465 × 650 px',
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
