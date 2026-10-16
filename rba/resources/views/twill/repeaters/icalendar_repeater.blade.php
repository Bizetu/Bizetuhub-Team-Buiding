@php
    use App\Repositories\CourseRepository;
    
    $courseOptions = app(CourseRepository::class)->published()->pluck('display_name', 'id');
@endphp

@twillRepeaterTitle('Industry Calendar')
@twillRepeaterTrigger('Add Industry Calendar Instance')

@formField('select', [
    'name' => 'course_id',
    'label' => 'Course',
    'placeholder' => 'Select Course',
    'options' => $courseOptions,
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
            'name'     => 'due_date',
            'label'    => 'Due Date',
            'minDate'  => '2026-01-01 12:00',
            'required' => true,
        ])
    @endslot
@endformColumns