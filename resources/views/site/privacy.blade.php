@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Privacy policy'])
@section('content')
<main class="mx-auto max-w-5xl px-6 py-20">
    @include('site.partials.page-hero-image')
    <article class="max-w-3xl">
        <div class="text-sm font-bold uppercase tracking-widest text-emerald-700">Privacy</div>
        <h1 class="mt-4 text-5xl font-black tracking-tight">{{ $pageContent?->header_title ?: 'Privacy policy' }}</h1>
        <p class="mt-5 text-sm text-slate-500">Draft for review before publication</p>
        <div class="mt-10 space-y-8 leading-8 text-slate-700">
            <section><h2 class="text-2xl font-black">Information we collect</h2><p class="mt-3">When you submit a booking request or contact form, we collect the details you provide, such as your name, work contact details, organisation and programme requirements.</p></section>
            <section><h2 class="text-2xl font-black">How we use information</h2><p class="mt-3">We use submitted information to respond to enquiries, assess and manage booking requests, and provide requested services. We do not treat a booking request as confirmation of a booking.</p></section>
            <section><h2 class="text-2xl font-black">Retention and access</h2><p class="mt-3">Access to request information is limited to authorised personnel who need it to respond or provide services. Information should be retained only for as long as needed for these purposes and applicable obligations.</p></section>
            <section><h2 class="text-2xl font-black">Your choices</h2><p class="mt-3">For questions about personal information you have shared with us, contact <a class="font-bold text-emerald-700" href="mailto:{{ $siteSettings['email'] }}">{{ $siteSettings['email'] }}</a>.</p></section>
            <p class="rounded-2xl bg-amber-50 p-5 text-sm leading-6 text-amber-950">This starter policy is not legal advice. Have it reviewed and adapted to your organisation, applicable privacy laws, retention practices, cookies, analytics, processors and contact procedures before publishing.</p>
        </div>
    </article>
</main>
@endsection
