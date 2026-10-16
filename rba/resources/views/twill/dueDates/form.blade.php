@extends('twill::layouts.form')

@section('contentFields')
    @php
        $time = \Carbon\Carbon::now()->format('Y-m-d');
        $user = Auth::user();
        $isSuperAdmin = $user->role_id === 1 || $user->is_superadmin === 1 || $user->role_value === 'SUPERADMIN';
    @endphp

    @if($course)
        @formField('select ', [
            'name' => 'course_id',
            'id' => 'course_id',
            'label' => 'Selected course',
            'placeholder' => 'Select a course',
            'min' => 1,
            'unpack' => false,
            'searchable' => true,
            'options' => collect($courseList),
            'required'=>true,
            'value' => $course,
            'disabled' =>true,
        ])
    @else

        @formField('multi_select ', [
            'name' => 'course_id[]',
            'id' => 'course_id',
            'label' => 'Select a course',
            'placeholder' => 'Select a course',
            'min' => 1,
            'unpack' => false,
            'searchable' => true,
            'options' => collect($courseList),
            'required'=>true,
            'value' => isset($selectedCourses) ? (array) $selectedCourses : []
        ])
    @endif

    @if($isSuperAdmin)
        @formField('select', [
            'name' => 'company_id',
            'id' => 'company_id',
            'label' => 'Select a company',
            'placeholder' => 'Select a company',
            'min' => 1,
            'unpack' => false,
            'searchable' => true,
            'options' => collect($companyList),
            'required'=>true,
            'default' => auth()->user()->company_id,
        ])
    @endif

    @if(!empty($activeJobRole))
        @formField('select ', [
            'name' => 'job_role_id',
            'id' => 'job_role_id',
            'label' => 'Selected job role',
            'placeholder' => 'Select job role',
            'unpack' => false,
            'searchable' => true,
            'options' => collect($jobRoleList),
            'required' => false,
            'value' => $selectedJobRoles[0],
            'note' => 'Empty for general',
            'disabled' => true,
        ])
    @else
        @formField('multi_select ', [
            'name' => 'job_role_id[]',
            'id' => 'job_role_id',
            'label' => 'Select job role',
            'placeholder' => 'Select job role',
            'unpack' => false,
            'searchable' => true,
            'options' => collect($jobRoleList),
            'required' => false,
            'value' => $activeJobRole,
            'note' => 'Empty for general'
        ])
    @endif

    @if($item->due_date)
        @formField('date_picker', [
            'name' => 'due_date',
            'label' => 'Course Deadline',
            'minDate' => $time,
            'required'=>true,
            'default' => optional($item->due_date)->format('Y-m-d'),
        ])
    @else
        @formField('date_picker', [
            'name' => 'due_date',
            'label' => 'Course due date',
            'minDate' => $time,
            'required'=>true,
            'default' => optional($item->due_date)->format('Y-m-d'),
        ])
    @endif

@stop
