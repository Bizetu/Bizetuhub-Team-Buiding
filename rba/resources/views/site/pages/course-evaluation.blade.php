@extends('layouts.app')

@section('title', 'Course Evaluations - Rwanda Bankers Association')

@section('content')
    <x-hero 
        title="Course Evaluation"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Course Evaluation', 'url' => '#']
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <div class="bg-gray-50">
        <div class="max-w-8xl md:max-w-5xl mx-auto py-4">
            
            <div class="mb-2">
                <p class="text-lg sm:text-xl text-[#1F3B67] leading-relaxed max-w-3xl">
                    {!! $pageItem->description !!}
                </p>
            </div>

            @if($sections)
                <div>
                    <form 
                        action="{{ route('course.evaluate.store') }}" 
                        method="post" 
                        id="editEvaluationForm" 
                        enctype="multipart/form-data"
                        class="space-y-8"
                    >
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $id }}" id="course_ev">
                        
                        @if($sections->blocks)
                            <div class="mt-0">
                                @foreach($sections->blocks as $block)
                                    @foreach($block->children as $item)
                                        @include('site.blocks.evaluation_repeater', ['item' => $item])
                                    @endforeach
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="pt-4 flex justify-center">
                            <button 
                                type="submit" 
                                class="relative inline-flex items-center justify-center px-4 py-2 text-lg font-medium text-white bg-[#1F3B67] rounded-xl overflow-hidden"
                            >
                                <span class="relative z-10 flex items-center">
                                    Submit
                                    <svg 
                                        class="w-5 h-5" 
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts') 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starContainers = document.querySelectorAll('.star-rating');
            
            starContainers.forEach(container => {
                const stars = container.querySelectorAll('.star-btn');
                const input = container.querySelector('.rating-value');
                const display = container.querySelector('.rating-display');
                const errorMsg = container.parentElement.querySelector('.rating-error');
                
                stars.forEach((star, index) => {
                    star.addEventListener('click', function() {
                        const rating = this.getAttribute('data-rating');
                        input.value = rating;
                        display.textContent = rating + '/10';
                        
                        // Remove error state when user selects a rating
                        container.classList.remove('error');
                        if (errorMsg) {
                            errorMsg.classList.add('hidden');
                        }
                        
                        // Remove active class from all stars
                        stars.forEach(s => s.classList.remove('active'));
                        
                        // Add active class to selected stars
                        for(let i = 0; i <= index; i++) {
                            stars[i].classList.add('active');
                        }
                    });
                    
                    star.addEventListener('mouseenter', function() {
                        for(let i = 0; i <= index; i++) {
                            stars[i].style.color = '#1F3B67';
                        }
                    });
                    
                    star.addEventListener('mouseleave', function() {
                        stars.forEach((s, i) => {
                            if (!s.classList.contains('active')) {
                                s.style.color = '';
                            }
                        });
                    });
                });
            });
            
            // Form validation
            const form = document.getElementById('editEvaluationForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;
                    let firstError = null;
                    
                    // Validate all star ratings
                    const allStarRatings = document.querySelectorAll('.star-rating[data-required="true"]');
                    allStarRatings.forEach(container => {
                        const input = container.querySelector('.rating-value');
                        const errorMsg = container.parentElement.querySelector('.rating-error');
                        
                        if (!input.value || input.value === '') {
                            isValid = false;
                            container.classList.add('error');
                            if (errorMsg) {
                                errorMsg.classList.remove('hidden');
                            }
                            
                            // Track first error for scrolling
                            if (!firstError) {
                                firstError = container;
                            }
                        } else {
                            container.classList.remove('error');
                            if (errorMsg) {
                                errorMsg.classList.add('hidden');
                            }
                        }
                    });
                    
                    // Prevent form submission if validation fails
                    if (!isValid) {
                        e.preventDefault();
                        
                        // Scroll to first error
                        if (firstError) {
                            firstError.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center' 
                            });
                        }
                        
                        // Show alert ONCE
                        alert('Please complete all required fields, including star ratings.');
                        
                        return false;
                    }
                });
            }
        });
    </script>
@endpush
