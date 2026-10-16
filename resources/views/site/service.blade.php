@extends('layouts.site', ['title' => $service->title])
@section('content')
<main class="services-experience relative isolate overflow-hidden text-white">
    <div class="services-glow services-glow--amber" aria-hidden="true"></div>
    <div class="services-glow services-glow--green" aria-hidden="true"></div>
    <div class="relative z-10 mx-auto max-w-7xl px-6 py-12 sm:py-16">
        <a href="{{ route('services') }}" class="service-back-link mb-7 inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold">
            <span aria-hidden="true">←</span> All programmes
        </a>
        @include('site.partials.service-detail-content', ['services' => $services, 'interactive' => false])
    </div>
    @include('site.partials.service-proposal-dialog')
</main>
@endsection
