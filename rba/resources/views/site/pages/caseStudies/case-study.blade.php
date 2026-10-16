@extends('layouts.app')

@section('title', 'Case Studies')


@section('content')
@include('site.includes.components.parallax', [
'image' => $pageItem->image('hero_image', 'default'),
'title' => $pageItem->title,
'text' => $caseStudy->title,

])

<div class="w-full flex flex-wrap px-10 py-10 text-black">
    <p>{{$caseStudy->description}}</p>
</div>

<div class="w-full max-w-xl flex flex-row gap-4 px-10 mb-10 text-[14px]">
    @if($caseStudy->file('pdf'))
        <div class="w-[150px] h-[150px] bg-white-500 flex items-center justify-center text-black px-2 shadow-[0_4px_12px_rgba(0,0,0,0.2)]">
            <p>{{$caseStudy->title}}</p>
        </div>
    @endif

    @if($caseStudy->file('ppt'))
        <div class="w-[150px] h-[150px] bg-white-500 flex items-center justify-center text-black px-2 shadow-[0_4px_12px_rgba(0,0,0,0.2)]">
            <p>{{$caseStudy->title}}</p>
        </div>
    @endif

    @if($caseStudy->file('video'))
        <div class="w-[150px] h-[150px] bg-white-500 flex items-center justify-center text-black px-2 shadow-[0_4px_12px_rgba(0,0,0,0.2)]">
            <p>{{$caseStudy->title}}</p>
        </div>
    @endif

</div>


@endsection
