@extends('layouts.error')
@section('title', '500 - Internal Server Error')
@section('content')

<div class="max-w-2xl w-full text-center">
    <div class="text-[12rem] md:text-[16rem] font-light leading-none mb-8 animate-float">500</div>
    <h1 class="text-4xl md:text-5xl font-light tracking-tight mb-6">Something went wrong!!!</h1>
    <p class="text-xl text-zinc-600 font-light mb-10">Please visit the homepage and proceed from there.</p>
    <a href="{{ url('/') }}" class="inline-block px-8 py-3 bg-black text-white hover:bg-zinc-900 transition-all hover:-translate-y-0.5">
        Return Home
    </a>
</div>

@endsection
