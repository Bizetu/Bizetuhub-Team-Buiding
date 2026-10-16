@extends('layouts.app')

@section('title', 'Case Studies')

@section('content')
@include('site.includes.components.parallax', [
    'image' => $pageItem->image('hero_image', 'default'),
    'title' => $pageItem->title,
    'text' =>  $pageItem->header_title,

])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 px-10 py-10">
    @foreach ($casestudies as $casestudy)
        @include('site.includes.components.case-study', ['casestudy' => $casestudy])
    @endforeach
</div>

@endsection
