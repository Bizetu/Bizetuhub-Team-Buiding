@extends('layouts.app')

@section('content')

    <x-hero 
        title="{{ $pageItem->header_title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Resources'],
            ['label' => 'Events', 'url' => 'events'],
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <x-resource-nav-tabs active="events" />

    <section class="relative min-h-[272px] lg:min-h-[400px] overflow-hidden max-w-8xl lg:max-w-7xl mx-auto">
        {!! $pageItem->renderBlocks() !!}
    </section>

@endsection