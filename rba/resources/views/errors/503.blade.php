@extends('layouts.error')
@section('title', '503 - Service Unavailable')
@section('content')

    <div class="max-w-2xl w-full text-center">
        <div class="text-[12rem] md:text-[16rem] font-light leading-none mb-8 animate-float">503</div>
        <h1 class="text-4xl md:text-5xl font-light tracking-tight mb-6">Under Maintenance</h1>
        <p class="text-xl text-zinc-600 font-light mb-10">We're performing scheduled maintenance. We'll be back shortly</p>
        <a href="{{ url('/') }}"
            class="inline-block px-8 py-3 bg-black text-white hover:bg-zinc-900 transition-all hover:-translate-y-0.5">
            Try Again
        </a>
    </div>

@endsection