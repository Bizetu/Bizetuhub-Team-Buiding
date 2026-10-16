@extends('layouts.app')

@section('title', 'Terms and Conditions - Rwanda Bankers Association')

@section('content')
    <section class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 my-40">
                <div class="lg:sticky lg:top-24 self-start">
                    <h1 class="text-6xl sm:text-7xl lg:text-8xl font-normal">
                        <span class="text-[#1F3B67]">{!! $pageItem->header_title !!}</span>
                    </h1>
                </div>

                <x-wysiwyg-div :content="$pageItem->description"/>
            </div>
        </div>
    </section>
@endsection