@if (!empty($block) && $block->hasImage('rwandas_green_growth_img'))
    <div class="flex items-end justify-center min-h-[175px] sm:min-h-[400px] md:min-h-[420px] lg:min-h-[440px] pb-2 sm:pb-6">
        <img
            src="{{ $block->image('rwandas_green_growth_img', 'default') }}"
            alt="Rwanda's Green Growth"
            loading="lazy"
            class="h-full w-auto max-w-full object-contain object-bottom"
            onerror="this.style.display='none'"
        />
    </div>
    @if ($block->input('cta_url'))
        <div class="pb-4 max-w-7xl mx-auto flex justify-center">
            <a href="{{ $block->input('cta_url') }}" class="inline-flex items-center gap-2 yellow-button-rounded-full">
                → {{ $block->input('cta_label') }}
            </a>
        </div>
    @endif
@endif