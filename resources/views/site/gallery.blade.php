@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Gallery'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $heroDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : 'Moments from our facilitated sessions and shared experiences.';
@endphp
<main>
    <section class="page-image-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image:linear-gradient(100deg,rgb(3 17 12 / 92%),rgb(3 17 12 / 75%),rgb(3 17 12 / 55%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'Gallery' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $pageContent?->header_title ?: 'A glimpse of teams learning together.' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-emerald-50/90 sm:text-lg sm:leading-8">{{ $heroDescription }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-14 sm:py-16">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($galleryItems as $item)
                @php($image = $item->image('image', 'default', [], true))
                <figure class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
                    @if($image)
                        <img src="{{ $image }}" alt="{{ $item->alt_text }}" class="aspect-[4/3] w-full object-cover" loading="lazy">
                    @else
                        <div class="flex aspect-[4/3] items-center justify-center bg-gradient-to-br from-emerald-700 via-teal-800 to-slate-950 px-8 text-center text-2xl font-bold text-white">
                            {{ $item->alt_text }}
                        </div>
                    @endif
                    <figcaption class="p-6">
                        @if($item->category)<div class="text-xs font-bold uppercase tracking-widest text-emerald-700">{{ $item->category }}</div>@endif
                        <h2 class="mt-2 text-xl font-black">{{ $item->title }}</h2>
                        @if($item->caption)<p class="mt-2 text-sm leading-6 text-slate-600">{{ strip_tags($item->caption) }}</p>@endif
                    </figcaption>
                </figure>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-slate-600 sm:col-span-2 lg:col-span-3">
                    Gallery images will appear here once theyâ€™re added in the Twill admin.
                </div>
            @endforelse
        </div>
        <div class="mt-10">{{ $galleryItems->links() }}</div>
    </section>
</main>
@endsection
