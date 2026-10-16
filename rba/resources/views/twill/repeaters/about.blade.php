@twillRepeaterTitle('About')

@formField('input', [
    'name'  => 'about_our_mission',
    'label' => 'Our Mission Title',
    'placeholder' => 'Our Mission',
])

@formField('medias', [
    'name'  => 'about_our_mission_icon',
    'label' => 'Our Mission',
])

@formField('wysiwyg', [
    'name'          => 'about_our_mission_description',
    'label'         => 'Description',
    'editSource'    => true,
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

@formField('input', [
    'name'  => 'about_our_vision',
    'label' => 'Our Vision Title',
    'placeholder' => 'Our Vision',
])

@formField('medias', [
    'name'  => 'about_our_vision_icon',
    'label' => 'Our Vision',
])

@formField('wysiwyg', [
    'name'          => 'about_our_vision_description',
    'label'         => 'Description',
    'editSource'    => true,
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

@formField('input', [
    'name'  => 'about_our_values',
    'label' => 'Our Values Title',
    'placeholder' => 'Our Values',
])

@formField('medias', [
    'name'  => 'about_our_values_icon',
    'label' => 'Our Values',
])

@formField('wysiwyg', [
    'name'          => 'about_our_values_description',
    'label'         => 'Description',
    'editSource'    => true,
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

@formField('select', [
    'name'    => 'publish_status',
    'label'   => 'Published',
    'required' => true,
    'options' => [
        ['value' => 1, 'label' => 'True'],
        ['value' => 0, 'label' => 'False'],
    ],
])
