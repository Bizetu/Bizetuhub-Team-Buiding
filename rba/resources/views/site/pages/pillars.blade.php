@extends('layouts.app')

@section('title', 'Pillars - Rwanda Bankers Association')

@section('content')
    <!-- Hero Section -->
    <x-hero title="{{ $pageItem->header_title }}" :breadcrumbs="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $pageItem->header_title, 'url' => $pageItem->key],
    ]" :backgroundImage="$pageItem->image('hero_image')" height="h-80" />

    <x-esg-nav-tabs active="pillars" />

    <div class="mx-auto px-4 py-6">
        <div class="pillar-desc text-lg text-gray-600 leading-relaxed px-4 md:px-8 lg:px-12 [&>p]:mb-3">
            {!! $pageItem->description !!}
        </div>
    </div>

<div
    class="mx-auto px-4 py-6 h-64 md:h-96 bg-contain bg-center bg-no-repeat mb-8"
    style="background-image: url('{{ $pageItem->image('pillars_image') }}');"
>
</div>
@endsection
