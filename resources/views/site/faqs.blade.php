@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Frequently asked questions'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $heroDescription = $pageContent?->description
        ? strip_tags($pageContent->description)
        : 'Find answers to common questions about our programmes, events and booking process.';
@endphp
<main>
    <section class="page-image-hero relative isolate overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image:linear-gradient(100deg,rgb(3 17 12 / 92%),rgb(3 17 12 / 75%),rgb(3 17 12 / 55%)),url('{{ $heroImage }}');background-position:center;background-size:cover;" @endif>
        <div class="mx-auto max-w-7xl px-6 py-20 md:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'FAQs' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">{{ $pageContent?->header_title ?: 'A few helpful answers.' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-emerald-50/90 sm:text-lg sm:leading-8">{{ $heroDescription }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-6 py-14 sm:py-16">
        <div class="mb-8 text-slate-600">Have a question that isnâ€™t covered? <a class="font-bold text-emerald-700" href="{{ route('contact') }}">Get in touch.</a></div>
        <div class="divide-y divide-slate-200 rounded-3xl bg-white px-7 shadow-sm ring-1 ring-slate-200">
            @foreach([
                ['How do we book a session?', 'Send a request through the booking form with your preferred date, team size and goals. Our team will follow up to discuss availability and a proposal. A request is not a confirmed booking.'],
                ['How much does a programme cost?', 'Pricing depends on the programme, group size, duration and venue. Share your requirements and weâ€™ll prepare a tailored proposal.'],
                ['Can you facilitate at our venue?', 'Yes. Include your preferred venue in the booking form. We can discuss venue options and practical arrangements with you.'],
                ['Can programmes be adapted for our team?', 'Yes. Weâ€™ll discuss your team, context and goals before recommending a suitable format and activities.'],
                ['What if we need to change or cancel a booking?', 'Please contact our team as soon as possible. Any cancellation or rescheduling terms will be agreed in your proposal and booking confirmation.'],
                ['Do you run public events?', 'Public events are listed on the Events page when scheduled. You can register interest from an event page, or request a private programme for your organisation.'],
                ['How should participants prepare?', 'Preparation depends on the programme. Weâ€™ll share any relevant information with your organiser before the session.'],
            ] as [$question, $answer])
                <details class="group py-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-lg font-bold">
                        {{ $question }}
                        <span class="text-emerald-700 transition group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <p class="mt-4 max-w-3xl leading-7 text-slate-600">{{ $answer }}</p>
                </details>
            @endforeach
        </div>
    </section>
</main>
@endsection
