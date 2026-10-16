@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Terms of use'])
@section('content')
<main class="mx-auto max-w-5xl px-6 py-20">
    @include('site.partials.page-hero-image')
    <article class="max-w-3xl">
        <div class="text-sm font-bold uppercase tracking-widest text-emerald-700">Terms</div>
        <h1 class="mt-4 text-5xl font-black tracking-tight">{{ $pageContent?->header_title ?: 'Website terms of use' }}</h1>
        <p class="mt-5 text-sm text-slate-500">Draft for review before publication</p>
        <div class="mt-10 space-y-8 leading-8 text-slate-700">
            <section><h2 class="text-2xl font-black">Website information</h2><p class="mt-3">Website information is provided for general information about our team-building services and events. Contact us to discuss your organisation’s requirements and receive a proposal.</p></section>
            <section><h2 class="text-2xl font-black">Booking requests</h2><p class="mt-3">Submitting a request does not create a confirmed booking. A booking is confirmed only after our team has reviewed availability and both parties have agreed the programme and applicable terms in writing.</p></section>
            <section><h2 class="text-2xl font-black">Changes and availability</h2><p class="mt-3">Event and service information may change. Any programme-specific payment, cancellation, rescheduling, safety and participation terms should be agreed in the written proposal or booking confirmation.</p></section>
            <section><h2 class="text-2xl font-black">Questions</h2><p class="mt-3">Contact us at <a class="font-bold text-emerald-700" href="mailto:{{ $siteSettings['email'] }}">{{ $siteSettings['email'] }}</a> with questions about these terms.</p></section>
            <p class="rounded-2xl bg-amber-50 p-5 text-sm leading-6 text-amber-950">This starter text is not legal advice. Have it reviewed and adapted to your business and applicable law before publication.</p>
        </div>
    </article>
</main>
@endsection
