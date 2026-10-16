@php
use App\Repositories\EvaluationOptionRepository;

$evaluationOptions = app(EvaluationOptionRepository::class)->published()->pluck('title', 'id');
@endphp

@twillRepeaterTitle('Evaluation Question')
@twillRepeaterTrigger('Add Evaluation Question')

@formField('input', [
    'name' => 'evaluation_question',
    'label' => 'Evaluation Question',
    'maxlength' => 200,
    'translated' => true,
])

@formField('select', [
    'name' => 'evaluation_option_id',
    'label' => 'Answer Type',
    'placeholder' => 'Select Type',
    'options' => $evaluationOptions,
])


@formConnectedFields([
    'fieldName' => 'type',
    'fieldValues' => 'radio',
    'renderForBlocks' => true
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
@endformConnectedFields

@formConnectedFields([
    'fieldName' => 'type',
    'fieldValues' => 'checkbox',
    'renderForBlocks' => true
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
