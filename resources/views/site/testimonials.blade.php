@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Client testimonials'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $heroDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : 'Hear how our team experiences have helped people connect, collaborate and grow.';
@endphp
<main>
    <section class="page-image-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image:linear-gradient(100deg,rgb(3 17 12 / 92%),rgb(3 17 12 / 75%),rgb(3 17 12 / 55%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'Client testimonials' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $pageContent?->header_title ?: 'Experiences, in their own words.' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-emerald-50/90 sm:text-lg sm:leading-8">{{ $heroDescription }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-14 sm:py-16">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($testimonials as $testimonial)
                <figure class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <div class="text-amber-500" aria-label="{{ $testimonial->rating }} out of 5 stars">{{ str_repeat('â˜…', $testimonial->rating) }}</div>
                    <blockquote class="mt-5 text-lg leading-8 text-slate-700">â€œ{{ strip_tags($testimonial->description) }}â€</blockquote>
                    <figcaption class="mt-7 border-t border-slate-200 pt-5 text-sm font-bold">{{ $testimonial->client ?: $testimonial->title }}</figcaption>
                </figure>
            @empty
                <p class="text-slate-600">Client stories will appear here as theyâ€™re added.</p>
            @endforelse
        </div>
        <div class="mt-10">{{ $testimonials->links() }}</div>
    </section>
</main>
@endsection
