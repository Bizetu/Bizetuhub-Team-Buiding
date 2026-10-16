@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Insights & resources'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $heroDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : 'Practical perspectives on leadership, teamwork and the culture we build together.';
@endphp
<main>
    <section class="page-image-hero insight-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image:linear-gradient(100deg,rgb(3 17 12 / 92%),rgb(3 17 12 / 75%),rgb(3 17 12 / 55%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'Insights & resources' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $pageContent?->header_title ?: 'Ideas for stronger teams.' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-emerald-50/90 sm:text-lg sm:leading-8">{{ $heroDescription }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-14 sm:py-16">
        <div class="grid gap-6 md:grid-cols-3">
            @forelse($insights as $insight)
                <article class="flex flex-col rounded-3xl bg-white p-7 shadow-sm ring-1 ring-slate-200">
                    <div class="text-sm font-bold uppercase tracking-widest text-emerald-700">{{ $insight->category }}</div>
                    <h2 class="mt-4 text-2xl font-black"><a href="{{ route('insights.show', $insight) }}" class="hover:text-emerald-700">{{ $insight->title }}</a></h2>
                    <p class="mt-3 flex-1 leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($insight->description), 180) }}</p>
                    <div class="mt-6 text-sm text-slate-500">{{ $insight->reading_time }} min read</div>
                </article>
            @empty
                <p class="text-slate-600">New resources are on the way.</p>
            @endforelse
        </div>
        <div class="mt-10">{{ $insights->links() }}</div>
    </section>
</main>
@endsection
