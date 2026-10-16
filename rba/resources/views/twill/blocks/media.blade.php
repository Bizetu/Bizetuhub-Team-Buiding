@twillBlockTitle('Media')
@twillBlockIcon('image')
 
<x-twill::medias
    name="image"
    label="Images"
    :max="20"
/>
 
<x-twill::files
    name="video"
    label="video"
    note="Video will overwrite previously selected images"
    :max="1"
/>
 
<x-twill::input
    name="caption"
    label="Caption"
    :maxlength="250"
    :translated="true"
/>
 
@php
    $options = [
        [
            'value' => 'cut',
            'label' => 'Cut'
        ],
        [
            'value' => 'fade',
            'label' => 'Fade In/Out'
        ]
    ];
@endphp
 
<x-twill::select
    name="effect"
    label="Transition effect"
    placeholder="Select transition effect"
    default="cut"
    :options="$options"
/>
 
<x-twill::color
    name="bg"
    label="Background color"
    note="Default is light grey (#E6E6E6)"
/>
 
<x-twill::input
    name="transition_timing"
    label="Transition Timing"
    note="Timing in ms (default is 5000ms)"
/>