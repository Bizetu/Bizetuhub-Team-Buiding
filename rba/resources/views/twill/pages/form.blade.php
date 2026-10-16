@extends('twill::layouts.form')

@php
    use App\Repositories\MenuTypeRepository;

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

@section('contentFields')

    @formColumns
        @slot('left')
            @formField('input', [
                'name' => 'key',
                'label' => 'Key',
                'maxlength' => 100
            ])
        @endslot
        @slot('right')
            @formField('input', [
                'name' => 'position',
                'label' => 'Position',
                'maxlength' => 100
            ])
        @endslot
    @endformColumns

    @formField('input', [
        'name' => 'header_title',
        'label' => 'Header Title',
        'maxlength' => 100
    ])

    @formField('wysiwyg', [
        'name' => 'description',
        'label' => 'Description',
        'maxlength' => 2000,
        'toolbarOptions' => $wysiwygOptions ?? [],
        'editSource' => true
    ])

    @if($item->key == 'pillars')
        @formField('medias', [
            'name' => 'pillars_image',
            'label' => 'Pillars image',
            'note' => 'Recommended size 1920 X 640 px',
        ])
    @endif

    @formField('medias', [
        'name' => 'hero_image',
        'label' => 'Hero image',
        'note' => 'Recommended size 1920 X 640 px',
    ])

    @if($item->key == 'events')
        @formField('block_editor', ['blocks' => ['resource_events'],'max' => 1])
    @elseif($item->key == 'external-links')
        @formField('block_editor', ['blocks' => ['resource_external_links'],'max' => 1])
    @elseif($item->key == 'research')
        @formField('block_editor', ['blocks' => ['resource_research'],'max' => 1])
    @elseif($item->key == 'videos')
        @formField('block_editor', ['blocks' => ['resource_videos'],'max' => 1])
    @elseif($item->key == 'pillars')
        @formField('block_editor', ['blocks' => ['pillars'],'max' => 1])
    @elseif($item->key == 'esg-guidelines')
        @formField('block_editor', ['blocks' => ['esg_guidelines'],'max' => 1])
    @elseif($item->key == 'icalendar')
        @formField('block_editor', ['blocks' => ['icalendar'],'max' => 1])
    @elseif($item->key == 'about')
        @formField('block_editor', ['blocks' => ['about'],'max' => 1])
    @elseif($item->key == 'home')
        @formField('block_editor', [
            'blocks' => ['slideshow', 'image_next_to_text', 'rwandas_green_growth'],
        ])
    @endif
@endsection
