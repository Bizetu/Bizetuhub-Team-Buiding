@extends('layouts.app')

@section('title', 'ESG Guidelines - Rwanda Bankers Association')

@section('content')
    <!-- Hero Section -->
    <x-hero title="{{ $pageItem->header_title }}" :breadcrumbs="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $pageItem->header_title, 'url' => $pageItem->key],
    ]" :backgroundImage="$pageItem->image('hero_image')" height="h-80" />

    <x-esg-nav-tabs active="esg-guidelines" />

    <div class="mx-auto py-6 px-4">
        <div class="pillar-desc text-lg text-gray-600 leading-relaxed px-4 md:px-8 lg:px-12 [&>p]:mb-3">
            {!! $pageItem->description !!}
        </div>
        {!! $pageItem->renderBlocks() !!}
    </div>
@endsection
