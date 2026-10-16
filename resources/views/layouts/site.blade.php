<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bizetu Hub brings together team building, HR advisory, corporate events and career support to help people and organisations thrive.">
    <meta name="theme-color" content="{{ $siteSettings['header_background_color'] }}">
    <link rel="icon" href="{{ $siteSettings['logo'] ?: asset('images/bizetu-hub-logo.png') }}">
    <title>{{ $title ?? $siteSettings['name'] }} · {{ $siteSettings['name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-theme site-theme--{{ $siteSettings['theme_mode'] }} bg-stone-50 text-slate-900 antialiased" style="--site-overlay: {{ $siteSettings['page_background_color'] && !$siteSettings['page_background_image'] ? 0 : $siteSettings['background_overlay'] }}; --site-background-color: {{ $siteSettings['page_background_color'] ?: $siteSettings['background_color'] }}; --site-text-color: {{ $siteSettings['text_color'] }}; --site-heading-color: {{ $siteSettings['heading_color'] }}; --site-primary-color: {{ $siteSettings['primary_color'] }}; --site-secondary-color: {{ $siteSettings['secondary_color'] }}; --site-third-color: {{ $siteSettings['third_color'] }}; --site-menu-color: {{ $siteSettings['menu_color'] }}; --site-menu-active-color: {{ $siteSettings['menu_color_active'] }}; --site-header-background-color: {{ $siteSettings['header_background_color'] }}; --site-footer-color: {{ $siteSettings['footer_color'] }}; --site-enroll-color: {{ $siteSettings['enroll_color'] }}; --site-progress-color: {{ $siteSettings['progress_color'] }}; --site-retake-color: {{ $siteSettings['retake_color'] }}; --site-completed-color: {{ $siteSettings['completed_color'] }}; @if($siteSettings['page_background_color'] && !$siteSettings['page_background_image']) --site-background-image: none; @elseif($siteSettings['background_image']) --site-background-image: url('{{ $siteSettings['background_image'] }}'); @endif">
    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-5 px-6 py-3.5">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3" aria-label="{{ $siteSettings['name'] }} home">
                <img src="{{ $siteSettings['logo'] ?: asset('images/bizetu-hub-logo.png') }}" alt="" class="h-12 w-12 rounded-full object-cover shadow-sm ring-1 ring-slate-200">
                <span class="flex flex-col text-lg font-black tracking-tight text-slate-950 sm:text-xl">{{ $siteSettings['name'] }}@if($siteSettings['tag_line'])<small class="text-xs font-medium tracking-normal text-slate-500">{{ $siteSettings['tag_line'] }}</small>@endif</span>
            </a>
            <nav id="site-navigation" class="hidden items-center gap-5 text-[13px] font-semibold text-slate-700 xl:flex" style="margin-left:auto;flex:none;" aria-label="Main navigation">
                @forelse($siteMenus->get('main', collect()) as $menuItem)
                    <a class="transition hover:text-emerald-700" href="{{ $menuItem->href }}" @if($menuItem->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $menuItem->title }}</a>
                @empty
                    <a class="transition hover:text-emerald-700" href="{{ route('services') }}">Services</a>
                    <a class="transition hover:text-emerald-700" href="{{ route('events') }}">Events</a>
                    <a class="transition hover:text-emerald-700" href="{{ route('industries') }}">Industries</a>
                    <a class="transition hover:text-emerald-700" href="{{ route('insights') }}">Insights</a>
                    <a class="transition hover:text-emerald-700" href="{{ route('gallery') }}">Gallery</a>
                    <a class="transition hover:text-emerald-700" href="{{ route('about') }}">About</a>
                    <a class="transition hover:text-emerald-700" href="{{ route('contact') }}">Contact</a>
                @endforelse
            </nav>
            <div class="flex items-center gap-3">
                <a href="{{ route('book') }}" class="hidden rounded-full bg-amber-400 px-5 py-3 text-sm font-extrabold text-emerald-950 shadow-sm transition hover:bg-amber-300 sm:inline-flex">
                    Request a proposal <span class="ml-2" aria-hidden="true">↗</span>
                </a>
                <button id="menu-toggle" type="button" class="rounded-xl border border-slate-300 px-3 py-2 text-sm font-bold text-slate-800 xl:hidden" aria-controls="mobile-navigation" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span aria-hidden="true">☰</span>
                </button>
            </div>
        </div>
        <nav id="mobile-navigation" class="hidden border-t border-slate-200 bg-white px-6 py-5 xl:hidden" aria-label="Mobile navigation">
            <div class="mx-auto grid max-w-7xl gap-4 text-sm font-semibold text-slate-800">
                @forelse($siteMenus->get('main', collect()) as $menuItem)
                    <a class="hover:text-emerald-700" href="{{ $menuItem->href }}" @if($menuItem->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $menuItem->title }}</a>
                @empty
                    <a class="hover:text-emerald-700" href="{{ route('services') }}">Services</a>
                    <a class="hover:text-emerald-700" href="{{ route('events') }}">Events</a>
                    <a class="hover:text-emerald-700" href="{{ route('industries') }}">Industries</a>
                    <a class="hover:text-emerald-700" href="{{ route('insights') }}">Insights</a>
                    <a class="hover:text-emerald-700" href="{{ route('gallery') }}">Gallery</a>
                    <a class="hover:text-emerald-700" href="{{ route('about') }}">About</a>
                    <a class="hover:text-emerald-700" href="{{ route('contact') }}">Contact</a>
                @endforelse
                <a href="{{ route('book') }}" class="mt-2 inline-flex justify-center rounded-full bg-amber-400 px-5 py-3 font-extrabold text-emerald-950">Request a proposal <span class="ml-2" aria-hidden="true">↗</span></a>
            </div>
        </nav>
    </header>

    @if(session('success'))
        <div class="mx-auto mt-6 max-w-5xl rounded-2xl bg-emerald-100 px-5 py-4 text-sm font-semibold text-emerald-900" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mx-auto mt-6 max-w-5xl rounded-2xl bg-rose-100 px-5 py-4 text-sm text-rose-900" role="alert">
            Please check the highlighted fields and try again.
        </div>
    @endif

    @yield('content')

    <footer class="mt-24 bg-emerald-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 py-16 md:grid-cols-4">
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <img src="{{ $siteSettings['logo'] ?: asset('images/bizetu-hub-logo.png') }}" alt="" class="h-12 w-12 rounded-full object-cover">
                    <span class="text-xl font-black">{{ $siteSettings['name'] }}</span>
                </a>
                <p class="mt-5 max-w-sm text-sm leading-7 text-emerald-100/75">{{ $siteSettings['tag_line'] ?: 'Practical people solutions that help organisations thrive and professionals move forward.' }}</p>
                <div class="mt-5 text-xs font-bold uppercase tracking-[0.16em] text-amber-300">People. Culture. Growth.</div>
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-[0.16em] text-amber-300">Explore</div>
                <div class="mt-4 grid gap-3 text-sm">
                    @forelse($siteMenus->get('footer', collect()) as $menuItem)
                        <a href="{{ $menuItem->href }}" @if($menuItem->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>{{ $menuItem->title }}</a>
                    @empty
                        <a href="{{ route('services') }}">Services</a>
                        <a href="{{ route('events') }}">Events</a>
                        <a href="{{ route('industries') }}">Industries</a>
                        <a href="{{ route('packages') }}">Packages</a>
                        <a href="{{ route('insights') }}">Insights</a>
                        <a href="{{ route('gallery') }}">Gallery</a>
                        <a href="{{ route('testimonials') }}">Testimonials</a>
                        <a href="{{ route('faqs') }}">FAQs</a>
                        <a href="{{ route('about') }}">Our story</a>
                        <a href="{{ route('privacy') }}">Privacy</a>
                        <a href="{{ route('terms') }}">Terms</a>
                        <a href="{{ route('contact') }}">Contact</a>
                    @endforelse
                </div>
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-[0.16em] text-amber-300">Get in touch</div>
                <p class="mt-4 text-sm leading-7 text-emerald-100/80">
                    <a href="mailto:{{ $siteSettings['email'] }}">{{ $siteSettings['email'] }}</a>
                    @if($siteSettings['phone'])<br><a href="tel:{{ $siteSettings['phone'] }}">{{ $siteSettings['phone'] }}</a>@endif
                    @if($siteSettings['office'])<br>{{ $siteSettings['office'] }}@endif
                    @if(!$siteSettings['office'])
                        <br>Nairobi, Kenya
                    @endif
                </p>
                @if($siteSettings['instagram'] || $siteSettings['linkedin'])
                    <div class="mt-4 flex gap-4 text-sm">
                        @if($siteSettings['instagram'])<a href="{{ $siteSettings['instagram'] }}" rel="noopener noreferrer">Instagram</a>@endif
                        @if($siteSettings['linkedin'])<a href="{{ $siteSettings['linkedin'] }}" rel="noopener noreferrer">LinkedIn</a>@endif
                    </div>
                @endif
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-[0.16em] text-amber-300">Let’s work together</div>
                <p class="mt-4 text-sm leading-7 text-emerald-100/80">Tell us where your people are headed. We’ll help you plan the next step.</p>
                <a href="{{ route('book') }}" class="mt-5 inline-flex rounded-full bg-amber-400 px-5 py-3 text-sm font-extrabold text-emerald-950 transition hover:bg-amber-300">Start a conversation <span class="ml-2" aria-hidden="true">↗</span></a>
            </div>
        </div>
        <div class="border-t border-white/10 px-6 py-5 text-center text-xs text-emerald-100/50">© {{ date('Y') }} {{ $siteSettings['name'] }}. All rights reserved.</div>
    </footer>
</body>
</html>
