@extends('twill::layouts.form')

@push('extra_css')
<style>.hidden-input-fields{display:none}.hint-text{display:block;font-size:12px;color:rgb(136,136,136);line-height:1.8;margin-top:2rem;text-align:end}</style>
@endpush

@section('contentFields')
    @php
        $courseTypes = DB::table('course_types')->pluck('title', 'id');
        $courses = DB::table('courses')->pluck('display_name', 'course_id');
        $courseCategories = app(App\Repositories\CourseCategoryRepository::class)->listAll('title');
        $wysiwygOptions = [
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
            'table',
            'align',
        ];
    @endphp

    <span class="hint-text">
        Instructor-paced courses progress at the pace that the course author sets. You can configure release dates for course content and due dates for assignments. Self-paced courses do not have release dates for course content or due dates for assignments. Learners can complete course material at any time before the course end date.
    </span>

    @formField('input', [
        'name'        => 'short_name',
        'label'       => 'Short Name',
        'maxlength'   => 100,
        'required'    => true,
        'note'        => 'No space or special characters allowed e.g. "Time-Management-Module-2"',
        'placeholder' => 'Course-Short-Name',
    ])

    @formField('input', [
        'name'        => 'display_name',
        'label'       => 'Display Name',
        'maxlength'   => 100,
        'note'        => 'e.g. "Time Management Module 2"',
        'required'    => true,
        'placeholder' => 'Course Display Name',
    ])

    @formField('radios', [
        'name'    => 'self_paced',
        'label'   => 'Course Pacing',
        'options' => [
            ['value' => 'instructor', 'label' => 'Instructor-Paced'],
            ['value' => 'self',       'label' => 'Self-Paced'],
        ],
    ])

    @formColumns
        @slot('left')
            @formField('date_picker', [
                'name'     => 'start',
                'label'    => 'Course Start Date',
                'minDate'  => '2017-09-10 12:00',
                'required' => true,
            ])
        @endslot

        @slot('right')
            @formField('date_picker', [
                'name'     => 'end',
                'label'    => 'Course End Date',
                'minDate'  => '2017-09-10 12:00',
                'required' => true,
            ])
        @endslot
    @endformColumns

    @formColumns
        @slot('left')
            @formField('date_picker', [
                'name'     => 'enrol_start',
                'label'    => 'Enrollment Start Date',
                'minDate'  => '2017-09-10 12:00',
                'required' => true,
            ])
        @endslot

        @slot('right')
            @formField('date_picker', [
                'name'     => 'enrol_end',
                'label'    => 'Enrollment End Date',
                'minDate'  => '2017-09-10 12:00',
                'required' => true,
            ])
        @endslot
    @endformColumns

    @formColumns
        @slot('left')
            @formField('date_picker', [
                'name'     => 'due_date',
                'label'    => 'Due Date',
                'minDate'  => '2017-09-10 12:00',
                'required' => false,
            ])
        @endslot

        @slot('right')
            @formField('select', [
                'name'    => '_pre_requisite_courses_json',
                'label'   => 'Prerequisite Course',
                'options' => collect($courses ?? []),
            ])
        @endslot
    @endformColumns

    @formColumns
        @slot('left')
            @formField('select', [
                'name'        => 'course_category_id',
                'label'       => 'Course Category',
                'placeholder' => 'Select a category',
                'options'     => collect($courseCategories ?? []),
            ])
        @endslot

        @slot('right')
            @formField('select', [
                'name'        => 'course_type_id',
                'label'       => 'Course Type',
                'placeholder' => 'Select a type',
                'options'     => collect($courseTypes ?? []),
            ])
        @endslot
    @endformColumns

    @formColumns
        @slot('left')
            @formField('input', [
                'name'        => 'effort',
                'label'       => 'Course Effort',
                'maxlength'   => 100,
                'required'    => true,
                'note'        => 'Estimated time for course completion e.g. 10:00',
                'placeholder' => '00:00',
            ])
        @endslot

        @slot('right')
            @formField('select', [
                'name'     => 'gradeable',
                'label'    => 'Gradeable',
                'note'     => 'Select "True" if the course is gradeable.',
                'required' => true,
                'options'  => [
                    ['value' => 1,  'label' => 'True'],
                    ['value' => 0, 'label' => 'False'],
                ],
            ])
        @endslot
    @endformColumns

    @formColumns
        @slot('left')
            @formField('input', [
                'name'        => 'pass_mark',
                'label'       => 'Pass Mark',
                'note'        => 'eg 0.8',
            ])
        @endslot
        @slot('right')
            @formField('input', [
                'name'        => 'position',
                'label'       => 'Position',
            ])
        @endslot
    @endformColumns

    @formField('input', [
        'name'      => 'short_description',
        'label'     => 'Short Description',
        'maxlength' => 150,
        'required'  => true,
        'type'      => 'textarea',
        'rows'      => 3,
    ])

    @formField('wysiwyg', [
        'name'      => 'overview',
        'label'     => 'Course Overview',
        'maxlength' => 200,
        'note'      => 'A brief summary of what the course covers',
        'toolbarOptions' => $wysiwygOptions ?? [],
        'editSource' => true
    ])

    @formField('wysiwyg', [
        'name'          => 'more_info',
        'label'         => 'More Info',
        'editSource'    => true,
        'maxlength'     => 1000,
        'note'          => 'Detailed course outline and additional information',
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

    @formField('medias', [
        'name'  => 'cover_image',
        'label' => 'Cover Image',
        'note'  => 'Recommended size: 1200 x 630px',
    ])

@endsection

<div class="hidden-input-fields">
    <input type="hidden" name="item_id"   value="{{ $item->id }}">
    <input type="hidden" name="course_id" value="{{ $item->course_id }}">
</div>