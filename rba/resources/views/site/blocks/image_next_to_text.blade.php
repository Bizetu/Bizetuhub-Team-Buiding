@if (!empty($block) && $block->hasImage('image_beside'))

    @php
        $imagePosition   = $block->input('image_position') ?? 'left';
        if ($imagePosition === 'right') {
            $imageOrderClass = 'order-1 md:order-2';
            $textOrderClass  = 'order-2 md:order-1';
        } else {
            $imageOrderClass = 'order-1 md:order-1';
            $textOrderClass  = 'order-2 md:order-2';
        }

        $captionTitle    = $block->input('image_caption_title');
        $captionSubtitle = $block->input('image_caption_subtitle');
        $description     = $block->input('description');

        $descriptionTruncated = $description
            ? (mb_strlen(strip_tags($description)) > 1500
                ? mb_substr(strip_tags($description), 0, 1500) . '…'
                : strip_tags($description))
            : null;

        $rawTitle       = $block->input('title') ?? '';
        $titleWords     = explode(' ', trim($rawTitle));
        $titleHighlight = array_pop($titleWords);
        $titleRest      = implode(' ', $titleWords);

        $content = [];
        for ($i = 1; $i <= 5; $i++) {
            $label = $block->input("feature_{$i}_label");
            $desc  = $block->input("feature_{$i}_description");
            if ($label || $desc) {
                $descTruncated = $desc
                    ? (mb_strlen(strip_tags($desc)) > 1500
                        ? mb_substr(strip_tags($desc), 0, 1500) . '…'
                        : strip_tags($desc))
                    : null;
                $content[] = [
                    'label'    => $label,
                    'desc'     => $descTruncated,
                    'hasIcon'  => $block->hasImage("feature_{$i}_icon"),
                    'iconSrc'  => $block->hasImage("feature_{$i}_icon")
                                    ? $block->image("feature_{$i}_icon", 'default')
                                    : null,
                ];
            }
        }
    @endphp

    <div class="vp-6">
        <div 
            class="
                flex flex-col items-start gap-8 md:gap-10 lg:gap-16
                {{ $imagePosition === 'right' ? 'md:flex-row-reverse' : 'md:flex-row' }}
            ">
            <div class="w-full md:w-2/5 {{ $imageOrderClass }} flex-shrink-0">
                <div class="w-full aspect-square overflow-hidden">
                    <img
                        src="{{ $block->image('image_beside', 'default') }}"
                        alt="{{ $captionTitle ?? $rawTitle }}"
                        class="w-full h-full object-cover object-top"
                    />
                </div>

                @if ($captionTitle || $captionSubtitle)
                    <div class="pt-3 pb-1">
                        @if ($captionTitle)
                            <p class="text-[#1F3B67] font-black text-xl sm:text-xl leading-tight tracking-tight">
                                {{ $captionTitle }}
                            </p>
                        @endif
                        @if ($captionSubtitle)
                            <p class="text-gray-500 text-lg sm:text-lg mt-1 font-normal">
                                {{ $captionSubtitle }}
                            </p>
                        @endif
                    </div>
                @endif

            </div>

            <div class="w-full md:w-3/5 {{ $textOrderClass }} flex flex-col justify-center">

                @if ($rawTitle)
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1F3B67] mb-5 leading-tight">
                        @if ($titleRest){{ $titleRest }} @endif<span class="text-[#FFB901]">{{ $titleHighlight }}</span>
                    </h2>
                @endif

                @if ($descriptionTruncated)
                    <p class="text-[#1f3b67] text-lg leading-relaxed mb-6 first-letter:text-7xl first-letter:font-black first-letter:text-heading first-letter:me-3 first-letter:float-start first-letter:text-[#1f3b67]">
                        {{ $descriptionTruncated }}
                    </p>
                @endif

                @if (count($content))
                    <div class="flex flex-col divide-y divide-gray-200 border-t border-gray-200 mb-8">
                        @foreach ($content as $feature)
                            <div class="flex items-start gap-4 py-4">

                                @if ($feature['hasIcon'])
                                    <div class="flex-shrink-0 w-10 h-10 mt-0.5">
                                        <img
                                            src="{{ $feature['iconSrc'] }}"
                                            alt="{{ $feature['label'] ?? '' }}"
                                            class="w-full h-full object-contain"
                                        />
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4 w-full">
                                    @if ($feature['label'])
                                        <p class="font-bold text-[#1F3B67] text-lg sm:text-lg whitespace-nowrap sm:w-36 flex-shrink-0 leading-snug pt-0.5">
                                            {{ $feature['label'] }}
                                        </p>
                                        <div class="hidden sm:block w-px bg-gray-300 self-stretch"></div>
                                    @endif
                                    @if ($feature['desc'])
                                        <p class="text-gray-700 text-lg leading-relaxed">
                                            {{ $feature['desc'] }}
                                        </p>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($block->input('cta_url'))
                    <div class="vp-4">
                        <a href="{{ $block->input('cta_url') }}" class="inline-flex items-center gap-2 yellow-button-rounded-full">
                            → {{ $block->input('cta_label') ?? 'Read More' }}
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif