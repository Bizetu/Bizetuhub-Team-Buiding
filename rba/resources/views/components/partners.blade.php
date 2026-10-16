@php
    use App\Models\Partner;
    $partners = Partner::published()->with('medias')->get();
    $perPartnerWidth = $partners->count();
@endphp

@if($partners->isNotEmpty())
    <div class="w-full max-w-7xl mx-auto px-3 py-3">
        <div class="flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-10">
            <h2 class="text-2xl md:text-4xl font-black text-center lg:text-left lg:flex-shrink-0">Partners</h2>

            <div class="overflow-hidden flex-1">
                <div class="flex animate-scroll" id="partnerSlider">
                    @foreach($partners as $partner)
                        <div class="flex-shrink-0 flex items-center justify-center p-3 w-1/3">
                            <img src="{{ $partner->hasImage('partner_logo', 'default') ? $partner->image('partner_logo', 'default') : asset('images/defaults/missing-partner.svg') }}"
                                alt="{{ $partner->title ?? 'Partner logo' }}"
                                class="max-h-16 w-auto object-contain"
                                loading="lazy">
                        </div>
                    @endforeach

                    @foreach($partners as $partner)
                        <div class="flex-shrink-0 flex items-center justify-center p-3 w-1/3">
                            <img src="{{ $partner->hasImage('partner_logo', 'default') ? $partner->image('partner_logo', 'default') : asset('images/defaults/missing-partner.svg') }}"
                                alt="{{ $partner->title ?? 'Partner logo' }}"
                                class="max-h-16 w-auto object-contain"
                                loading="lazy">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-scroll {
            animation: scroll 5s linear infinite;
            will-change: transform;
        }
        .animate-scroll:hover {
            animation-play-state: paused;
        }
    </style>
    @endpush
@endif
