@extends('layouts.site', ['title' => $pageContent?->header_title ?: $pageContent?->title ?: 'Contact'])
@section('content')
@php
    $heroImage = $pageContent?->image('hero', 'default', [], true);
    $contactBlocks = $pageContent?->blocks ?? collect();
    $contentSections = $contactBlocks->where('type', 'content_section');
    $callToActions = $contactBlocks->where('type', 'call_to_action');
    $mapLocation = $siteSettings['office'] ?: 'Nairobi, Kenya';
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapLocation);
    $mapEmbedUrl = 'https://maps.google.com/maps?q='.rawurlencode($mapLocation).'&output=embed';
@endphp

<style>
    .contact-hero { display:flex; min-height:350px; align-items:center; }
    .contact-hero > div { width:100%; }
</style>

<main @class([
    'contact-page',
    'bg-transparent text-emerald-50' => $siteSettings['theme_mode'] === 'dark',
    'bg-transparent text-[#17352a]' => $siteSettings['theme_mode'] !== 'dark',
])>
    
    <!-- HERO SECTION -->
    <section class="contact-hero relative overflow-hidden bg-[#0a1f1a] text-white" @if($heroImage) style="background-image: linear-gradient(120deg, rgb(10 31 26 / 94%), rgb(10 31 26 / 78%)), url('{{ $heroImage }}'); background-size: cover; background-position: center;" @endif>
        <div class="mx-auto max-w-6xl px-6 py-16 sm:py-24">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-amber-300">{{ $pageContent?->title ?: 'Contact' }}</p>
                <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight sm:text-5xl">{{ $pageContent?->header_title ?: "Let's talk about your people." }}</h1>
                @if($pageContent?->description)
                    <p class="mt-6 max-w-2xl text-base leading-7 text-emerald-50/85 sm:text-lg">{{ strip_tags($pageContent->description) }}</p>
                @endif
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT GRID (Contact Info + Form) -->
    <!-- Using items-stretch to make the left column match the height of the form -->
    <section class="mx-auto grid max-w-6xl items-stretch gap-10 px-6 py-12 sm:py-16 lg:grid-cols-2 lg:gap-16">
        
        <!-- LEFT COLUMN: Contact Info -->
        <!-- flex flex-col justify-between ensures text is at top, cards are spread evenly or pushed down -->
        <div class="flex flex-col justify-between py-2">
            <div>
                @forelse($contentSections as $block)
                    @if($block->translatedInput('eyebrow'))
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">{{ $block->translatedInput('eyebrow') }}</p>
                    @endif
                    <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">{{ $block->translatedInput('title') ?: 'Let’s Connect' }}</h2>
                    @if($block->translatedWysiwyg('text'))
                        <div class="prose prose-slate mt-5 max-w-xl leading-7 text-slate-600">{!! $block->translatedWysiwyg('text') !!}</div>
                    @else
                        <p class="mt-4 max-w-xl text-base leading-7 text-slate-600">We’d love to hear from you. Reach through any of the channels below, or fill out the form and we’ll get back to you within 24 hours.</p>
                    @endif
                    @if($block->hasImage('image', 'desktop'))
                        <img src="{{ $block->image('image', 'desktop', [], true) }}" alt="{{ $block->imageAltText('image') }}" class="mt-6 max-h-48 w-full rounded-2xl object-cover shadow-sm">
                    @endif
                @empty
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">Let’s Connect</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">We’re here to help</h2>
                    <p class="mt-4 max-w-xl text-base leading-7 text-slate-600">We’d love to hear from you. Reach through any of the channels below, or fill out the form and we’ll get back to you within 24 hours.</p>
                @endforelse
            </div>

            <!-- Contact Cards Container -->
            <!-- mt-10 pushes it away from text. gap-4 ensures uniform spacing -->
            <div class="mt-10 grid flex-1 content-start gap-4">
                <a href="mailto:{{ $siteSettings['email'] }}" class="group flex min-h-[80px] items-center gap-5 rounded-2xl bg-white px-6 py-5 shadow-sm ring-1 ring-[#17352a]/[0.06] transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-700" aria-hidden="true">✉</span>
                    <span class="min-w-0">
                        <span class="block text-base font-extrabold">Email Us</span>
                        <span class="mt-1 block break-all text-base text-amber-700">{{ $siteSettings['email'] }}</span>
                    </span>
                </a>
                
                <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="group flex min-h-[80px] items-center gap-5 rounded-2xl bg-white px-6 py-5 shadow-sm ring-1 ring-[#17352a]/[0.06] transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-700" aria-hidden="true">⌖</span>
                    <span>
                        <span class="block text-base font-extrabold">Visit Us</span>
                        <span class="mt-1 block text-base text-slate-600">{{ $mapLocation }}</span>
                    </span>
                </a>
                
                @if($siteSettings['phone'])
                    <a href="tel:{{ preg_replace('/[^+0-9]/', '', $siteSettings['phone']) }}" class="group flex min-h-[80px] items-center gap-5 rounded-2xl bg-white px-6 py-5 shadow-sm ring-1 ring-[#17352a]/[0.06] transition duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-700" aria-hidden="true">☎</span>
                        <span>
                            <span class="block text-base font-extrabold">Call Us</span>
                            <span class="mt-1 block text-base text-amber-700">{{ $siteSettings['phone'] }}</span>
                        </span>
                    </a>
                @endif
                
                @if($siteSettings['working_hours'])
                    <div class="flex min-h-[80px] items-center gap-5 rounded-2xl bg-white px-6 py-5 shadow-sm ring-1 ring-[#17352a]/[0.06]">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-700" aria-hidden="true">◷</span>
                        <span>
                            <span class="block text-base font-extrabold">Working Hours</span>
                            <span class="mt-1 block whitespace-pre-line text-sm leading-5 text-slate-600">{{ $siteSettings['working_hours'] }}</span>
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT COLUMN: Contact Form -->
        <!-- h-full ensures it stretches. flex-col allows pushing the button to bottom -->
        <form method="POST" action="{{ route('contact.store') }}" class="flex h-full flex-col rounded-3xl bg-white p-6 shadow-[0_18px_55px_-25px_rgba(10,31,26,0.35)] ring-1 ring-[#17352a]/[0.08] sm:p-8 lg:p-10">
            @csrf
            <h2 class="text-2xl font-black tracking-tight sm:text-3xl">Send a Message</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">Fill in the details below and we’ll get back to you promptly.</p>
            
            <!-- flex-1 allows the inputs to take up remaining vertical space if needed -->
            <div class="mt-8 flex-1 grid gap-5 sm:grid-cols-2">
                <label class="text-sm font-bold text-slate-900">Full name <span class="text-emerald-700">*</span>
                    <input name="name" type="text" value="{{ old('name') }}" required autocomplete="name" placeholder="e.g. John Doe" class="mt-2 w-full rounded-lg border border-[#d9ddd6] bg-[#fbfaf5] px-4 py-3.5 text-base font-normal text-slate-900 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10">
                    @error('name')<span class="mt-2 block text-xs font-normal text-emerald-700">{{ $message }}</span>@enderror
                </label>
                
                <label class="text-sm font-bold text-slate-900">Email address <span class="text-emerald-700">*</span>
                    <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="e.g. john@example.com" class="mt-2 w-full rounded-lg border border-[#d9ddd6] bg-[#fbfaf5] px-4 py-3.5 text-base font-normal text-slate-900 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10">
                    @error('email')<span class="mt-2 block text-xs font-normal text-emerald-700">{{ $message }}</span>@enderror
                </label>
                
                <label class="text-sm font-bold text-slate-900 sm:col-span-2">Organisation
                    <input name="organisation" type="text" value="{{ old('organisation') }}" autocomplete="organization" placeholder="Your organisation" class="mt-2 w-full rounded-lg border border-[#d9ddd6] bg-[#fbfaf5] px-4 py-3.5 text-base font-normal text-slate-900 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10">
                    @error('organisation')<span class="mt-2 block text-xs font-normal text-emerald-700">{{ $message }}</span>@enderror
                </label>
                
                <label class="text-sm font-bold text-slate-900 sm:col-span-2">Phone number
                    <input name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" placeholder="e.g. +254 700 000 000" class="mt-2 w-full rounded-lg border border-[#d9ddd6] bg-[#fbfaf5] px-4 py-3.5 text-base font-normal text-slate-900 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10">
                    @error('phone')<span class="mt-2 block text-xs font-normal text-emerald-700">{{ $message }}</span>@enderror
                </label>

                <label class="text-sm font-bold text-slate-900 sm:col-span-2">I'm interested in <span class="text-emerald-700">*</span>
                    <select name="interest" required class="mt-2 w-full rounded-lg border border-[#d9ddd6] bg-[#fbfaf5] px-4 py-3.5 text-base font-normal text-slate-900 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10">
                        <option value="" disabled @selected(old('interest') === null)>— Please select —</option>
                        @foreach($contactInterests as $interestOption)
                            <option value="{{ $interestOption->title }}" @selected(old('interest') === $interestOption->title)>{{ $interestOption->title }}</option>
                        @endforeach
                    </select>
                    @error('interest')<span class="mt-2 block text-xs font-normal text-emerald-700">{{ $message }}</span>@enderror
                </label>
                
                <label class="flex flex-col text-sm font-bold text-slate-900 sm:col-span-2">Message <span class="text-emerald-700">*</span>
                    <textarea name="message" required rows="5" placeholder="Tell us about your needs, goals, or questions..." class="mt-2 flex-1 w-full rounded-lg border border-[#d9ddd6] bg-[#fbfaf5] px-4 py-3.5 text-base font-normal text-slate-900 outline-none transition focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10">{{ old('message') }}</textarea>
                    @error('message')<span class="mt-2 block text-xs font-normal text-emerald-700">{{ $message }}</span>@enderror
                </label>
            </div>
            
            <button class="mt-8 w-full rounded-full px-5 py-4 text-base font-extrabold shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" style="background-color:#facc15;color:#0a1f1a;">Send Message</button>
        </form>
    </section>

    <!-- MAP SECTION -->
    <!-- Aligned to the same max-w-6xl container -->
    <section class="mx-auto max-w-6xl px-6 pb-16 sm:pb-24">
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-[#17352a]/[0.06] sm:p-8 lg:p-10">
            <div class="text-center">
                <h2 class="text-2xl font-black tracking-tight sm:text-3xl"><span class="mr-2 text-emerald-700" aria-hidden="true">♥</span>Find Us{{ $siteSettings['office'] ? ' in '.$siteSettings['office'] : '' }}</h2>
                <p class="mt-3 text-sm text-slate-500 sm:text-base">We’re based in {{ $mapLocation }} — easily accessible from all parts of the city.</p>
            </div>
            <div class="mt-8 overflow-hidden rounded-2xl border border-[#e8e3d6] bg-[#fbfaf5] p-2">
                <iframe src="{{ $mapEmbedUrl }}" title="Map showing {{ $mapLocation }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="h-64 w-full rounded-xl sm:h-80"></iframe>
                <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="mt-3 block text-center text-xs font-bold text-emerald-800 hover:underline">Open in Google Maps</a>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTIONS -->
    @foreach($callToActions as $block)
        @php
            $ctaUrl = $block->input('button_url') ?: route('book');
            $ctaLabel = $block->translatedInput('button_label') ?: 'Book Your Session Today';
        @endphp
        <section class="mx-auto max-w-6xl px-6 pb-16 sm:pb-24">
            <div class="relative overflow-hidden rounded-3xl bg-[#17352a] px-6 py-12 text-center text-white shadow-xl sm:px-12 sm:py-16">
                <div class="pointer-events-none absolute -right-16 -top-24 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl"></div>
                <div class="relative">
                    @if($block->translatedInput('eyebrow'))
                        <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-amber-300">{{ $block->translatedInput('eyebrow') }}</p>
                    @endif
                    <h2 class="mx-auto mt-3 max-w-3xl text-3xl font-black leading-tight sm:text-4xl">{{ $block->translatedInput('title') ?: 'Ready to build a thriving workplace or grow your career?' }}</h2>
                    @if($block->translatedWysiwyg('text'))
                        <div class="prose prose-invert mx-auto mt-4 max-w-2xl text-base leading-7 text-emerald-50/80">{!! $block->translatedWysiwyg('text') !!}</div>
                    @endif
                    <a href="{{ $ctaUrl }}" class="mt-8 inline-flex rounded-full bg-amber-400 px-8 py-4 text-sm font-extrabold text-[#17352a] shadow-sm transition hover:-translate-y-0.5 hover:bg-amber-300 hover:shadow-md">{{ $ctaLabel }}</a>
                </div>
            </div>
        </section>
    @endforeach
    
</main>
@endsection
