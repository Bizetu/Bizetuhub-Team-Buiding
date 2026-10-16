@php
    use App\Models\Evaluation;
@endphp

<fieldset class="pt-2 max-w-8xl md:max-w-5xl">
    <label class="block text-xl font-medium text-[#1F3B67] my-2">
        {{ $item->content['evaluation_question']['en'] }}
        <span class="text-red-500">*</span>
    </label>
    
    @if($item->content['evaluation_option_id'] == 2)
        {{-- Star Rating --}}
        <div class="star-rating flex items-center gap-2 my-2" data-required="true">
            @for($i = 1; $i <= 10; $i++)
                <button 
                    type="button"
                    class="star-btn text-2xl text-gray-200 hover:text-[#1F3B67] transition-colors duration-200 focus:outline-none"
                    data-rating="{{ $i }}"
                    aria-label="Rate {{ $i }} out of 10"
                >
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </button>
            @endfor
            <input 
                type="hidden" 
                name="{{ $item->content['evaluation_question']['en'] }}" 
                class="rating-value" 
                value="" 
                data-required="true"
                data-question="{{ $item->content['evaluation_question']['en'] }}"
            >
            <span class="ml-2 text-lg font-medium text-[#1F3B67] rating-display"></span>
        </div>
        <p class="mt-2 text-lg text-red-600 hidden rating-error">Please select a rating for this question.</p>

    @elseif($item->content['evaluation_option_id'] == 1)
        <textarea 
            name="{{ $item->content['evaluation_question']['en'] }}" 
            rows="5"
            data-required="true"
            class="textarea-field w-full px-4 py-3 text-lg text-[#1F3B67] bg-white border border-[#1F3B67] rounded-md focus:outline-none focus:ring-2 focus:ring-[#1F3B67] focus:border-transparent transition-all duration-200 resize-none hover:border-[#1F3B67]"
            placeholder="Share your thoughts..."
        ></textarea>
        <p class="mt-2 text-lg text-red-600 hidden textarea-error">This field is required.</p>

    @else
        <div class="space-y-3" data-radio-group="{{ $item->content['evaluation_question']['en'] }}" data-required="true">
            {!! Evaluation::getRadio($item->content['evaluation_option_id'], $item->content['evaluation_question']['en']) !!}
        </div>
        <p class="mt-2 text-lg text-red-600 hidden radio-error">Please select an option.</p>
    @endif
</fieldset>