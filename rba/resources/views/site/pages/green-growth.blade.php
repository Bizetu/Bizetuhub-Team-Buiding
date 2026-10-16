@extends('layouts.app')

@section('title', 'Rwanda\'s Green Growth - Rwanda Bankers Association')

@section('content')

    <x-hero
        title="{!! $pageItem->header_title !!}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $pageItem->header_title, 'url' => $pageItem->key],
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <section class="relative min-h-[272px] lg:min-h-[325px] overflow-hidden mx-auto my-4 container">
        <x-wysiwyg-div :content="$pageItem->description"/>
    </section>

@endsection
