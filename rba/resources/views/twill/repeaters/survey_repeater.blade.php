@twillRepeaterTitle('Survey Question')
@twillRepeaterTrigger('Add Question')
@formField('input', [
    'name' => 'survey_question',
    'label' => 'Question',
    'maxlength' => 200,
])

{{--@formField('select', [
    'name' => 'show_in_analytics',
    'label' => 'Show in Analytics',
    'placeholder' => 'Select',
    'options' => [
        [
            'value' => 1,
            'label' => 'Yes',
        ],
        [
            'value' => 0,
            'label' => 'No',
        ]
    ],
])

@formConnectedFields([
    'fieldName' => 'show_in_analytics',
    'fieldValues' => 1,
    'renderForBlocks' => true
])

    @formField('select', [
        'name' => 'analytics_type',
        'label' => 'Graph Type',
        'placeholder' => 'Select',
        'options' => [
            [
                'value' => 'bar',
                'label' => 'Bar graph',
            ],
            [
                'value' => 'line',
                'label' => 'Line graph',
            ],
            [
                'value' => 'pie',
                'label' => 'Pie chart',
            ],
        ],
    ])

@endformConnectedFields --}}


@formField('select', [
    'name' => 'type',
    'label' => 'Answer Type',
    'placeholder' => 'Select Type',
    'options' => [
        [
            'value' => 'text',
            'label' => 'Text',
        ],
        [
            'value' => 'bar',
            'label' => 'Bar',
        ],
        [
            'value' => 'radio',
            'label' => 'Radio',
        ],
        [
            'value' => 'checkbox',
            'label' => 'Checkbox',
        ],
    ],
])


@formConnectedFields([
    'fieldName' => 'type',
    'fieldValues' => ['radio'],
    'renderForBlocks' => true,
    'arrayContains' => true,
    'keepAlive' => true,
])
    @formField('input', [
        'name' => 'value_1',
        'label' => 'Answer 1',
    ])
    @formField('input', [
        'name' => 'value_2',
        'label' => 'Answer 2',
    ])
    @formField('input', [
        'name' => 'value_3',
        'label' => 'Answer 3',
    ])
    @formField('input', [
        'name' => 'value_4',
        'label' => 'Answer 4',
    ])
    @formField('input', [
        'name' => 'value_5',
        'label' => 'Answer 5',
    ])
    @formField('input', [
        'name' => 'value_6',
        'label' => 'Answer 6',
    ])
    @formField('input', [
        'name' => 'value_7',
        'label' => 'Answer 7',
    ])
    @formField('input', [
        'name' => 'value_8',
        'label' => 'Answer 8',
    ])
    @formField('input', [
        'name' => 'value_9',
        'label' => 'Answer 9',
    ])
    @formField('input', [
        'name' => 'value_10',
        'label' => 'Answer 10',
    ])
@endformConnectedFields

@formConnectedFields([
    'fieldName' => 'type',
    'fieldValues' => ['checkbox'],
    'renderForBlocks' => true,
    'arrayContains' => true,
    'keepAlive' => true,
])
    @formField('input', [
        'name' => 'value_1',
        'label' => 'Answer 1',
    ])
    @formField('input', [
        'name' => 'value_2',
        'label' => 'Answer 2',
    ])
    @formField('input', [
        'name' => 'value_3',
        'label' => 'Answer 3',
    ])
    @formField('input', [
        'name' => 'value_4',
        'label' => 'Answer 4',
    ])
    @formField('input', [
        'name' => 'value_5',
        'label' => 'Answer 5',
    ])
    @formField('input', [
        'name' => 'value_6',
        'label' => 'Answer 6',
    ])
    @formField('input', [
        'name' => 'value_7',
        'label' => 'Answer 7',
    ])
    @formField('input', [
        'name' => 'value_8',
        'label' => 'Answer 8',
    ])
    @formField('input', [
        'name' => 'value_9',
        'label' => 'Answer 9',
    ])
    @formField('input', [
        'name' => 'value_10',
        'label' => 'Answer 10',
    ])
@endformConnectedFields
