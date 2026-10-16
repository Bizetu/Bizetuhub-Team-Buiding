@auth
    @php
        $controller = new App\Http\Controllers\Front\SurveyController();
        $activeSurvey = $controller->getActiveSurvey();
        $showSurveyButton = false;

        if ($activeSurvey) {
            $hasSubmitted = App\Models\UserSurvey::where('survey_id', $activeSurvey->id)
                ->where('user_id', Auth::id())
                ->whereDate('created_at', '>=', now()->startOfQuarter())
                ->exists();

            $showSurveyButton = !$hasSubmitted;
        }
    @endphp

    @if($showSurveyButton)
        <div x-data="Survey()" x-init="init()">
            <button
                @click="open = true; load()"
                title="Take Survey"
                class="fixed bottom-12 right-4 z-50 w-12 h-12 flex items-center justify-center rounded-full bg-[#1f3b67] text-white shadow-md animate-bounce hover:[animation-play-state:paused] transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-5 h-5 fill-white">
                    <path d="M311.4 32l8.6 0c35.3 0 64 28.7 64 64l0 352c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 96C0 60.7 28.7 32 64 32l8.6 0C83.6 12.9 104.3 0 128 0L256 0c23.7 0 44.4 12.9 55.4 32zM248 112c13.3 0 24-10.7 24-24s-10.7-24-24-24L136 64c-13.3 0-24 10.7-24 24s10.7 24 24 24l112 0zM128 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm32 0c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zm0 128c0 13.3 10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0c-13.3 0-24 10.7-24 24zM96 416a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/>
                </svg>
            </button>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 flex items-center justify-center md:p-4 bg-black/60"
                @click.self="close()"
                style="display: none;"
            >
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full md:max-w-4xl bg-white shadow-2xl flex overflow-hidden max-h-[90vh]"
                    style="display: none;"
                >
                    <div class="hidden md:flex w-72 flex-shrink-0 bg-[#1f3b67] text-white p-8 flex-col justify-between">
                        <div>
                            <h2 class="text-2xl text-white font-bold leading-snug mb-4" x-text="data?.survey?.title ?? 'Loading...'"></h2>
                            <p class="text-lg text-white leading-relaxed" x-text="data?.survey?.description ?? ''"></p>
                        </div>

                        <template x-if="data">
                            <div class="mt-8">
                                <div class="text-base text-white mb-2">
                                    Question <span x-text="currentQuestion + 1"></span> of <span x-text="data.questions.length"></span>
                                </div>
                                <div class="w-full bg-gray-50 rounded-full h-1.5">
                                    <div
                                        class="bg-[#ffb901] h-1.5 rounded-full transition-all duration-300"
                                        :style="`width: ${((currentQuestion + 1) / data.questions.length) * 100}%`"
                                    ></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
                        <button
                            @click="close()"
                            class="absolute top-6 right-6 p-2 font-semibold bg-[#1F3B67] text-white rounded-full transition-all duration-200 hover:scale-110 active:scale-95 z-10 border-none"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="loading" class="flex-1 flex items-center justify-center p-12">
                            <div class="text-center text-gray-400">
                                <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <p class="text-lg">Loading survey...</p>
                            </div>
                        </div>

                        <div x-show="message && !loading" class="flex-1 flex items-center justify-center p-12">
                            <p class="text-center text-lg" :class="messageType === 'error' ? 'text-red-500' : 'text-gray-500'" x-text="message"></p>
                        </div>

                        <form
                            x-show="data && !loading && !message"
                            @submit.prevent="submit()"
                            class="flex-1 flex flex-col overflow-hidden"
                        >
                            <input type="hidden" x-model="surveyId">
                            <input type="hidden" x-model="userId">

                            <div class="flex-1 overflow-y-auto p-6 md:p-10">
                                <template x-for="(q, i) in (data?.questions ?? [])" :key="i">
                                    <div x-show="currentQuestion === i" class="h-full">

                                        {{-- Mobile-only progress bar --}}
                                        <template x-if="data">
                                            <div class="md:hidden mb-6">
                                                <div class="flex justify-between text-sm text-gray-400 mb-1.5">
                                                    <span>Question <span x-text="currentQuestion + 1"></span> of <span x-text="data.questions.length"></span></span>
                                                </div>
                                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                                    <div
                                                        class="bg-[#ffb901] h-1.5 rounded-full transition-all duration-300"
                                                        :style="`width: ${((currentQuestion + 1) / data.questions.length) * 100}%`"
                                                    ></div>
                                                </div>
                                            </div>
                                        </template>

                                        <p class="text-sm md:text-base font-semibold uppercase tracking-widest text-gray-400 mb-4 md:mb-6">
                                            Question <span x-text="q.number"></span> of <span x-text="data.questions.length"></span>
                                        </p>

                                        <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-6 md:mb-8 leading-snug">
                                            <span x-text="q.question"></span><span class="text-red-500">*</span>
                                        </h3>

                                        {{-- Text --}}
                                        <template x-if="q.type === 'text'">
                                            <textarea
                                                :name="`answer_${q.number}`"
                                                x-model="answers[q.number]"
                                                rows="6"
                                                placeholder="Type your answer here..."
                                                @keydown.space.stop
                                                class="w-full border border-gray-200 rounded-lg p-4 text-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1f3b67] resize-vertical transition"
                                            ></textarea>
                                        </template>

                                        {{-- Radio --}}
                                        <template x-if="q.type === 'radio'">
                                            <div class="flex flex-col gap-3">
                                                <p class="text-base text-gray-400 italic mb-1">Please select one answer.</p>
                                                <template x-for="val in q.values" :key="val">
                                                    <label class="group flex items-center gap-3 cursor-pointer">
                                                        <input
                                                            type="radio"
                                                            :name="`answer_${q.number}`"
                                                            :value="val"
                                                            x-model="answers[q.number]"
                                                            class="hidden"
                                                        >
                                                        <span
                                                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all duration-150"
                                                            :class="answers[q.number] === val
                                                                ? 'border-[#1f3b67] bg-[#1f3b67]'
                                                                : 'border-gray-300 group-hover:border-[#1f3b67]'"
                                                        >
                                                            <span
                                                                class="w-2 h-2 rounded-full bg-white transition-all"
                                                                :class="answers[q.number] === val ? 'opacity-100' : 'opacity-0'"
                                                            ></span>
                                                        </span>
                                                        <span
                                                            class="flex-1 px-4 md:px-5 py-3 rounded-lg border text-base md:text-lg text-gray-900 font-medium transition-all duration-150"
                                                            :class="answers[q.number] === val
                                                                ? 'bg-[#1f3b67] border-[#1f3b67] text-white'
                                                                : 'border-gray-200 group-hover:border-[#1f3b67]'"
                                                            x-text="val"
                                                        ></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </template>

                                        {{-- Checkbox --}}
                                        <template x-if="q.type === 'checkbox'">
                                            <div class="flex flex-col gap-3">
                                                <p class="text-base text-gray-400 italic mb-1">You may select more than one answer.</p>
                                                <template x-for="val in q.values" :key="val">
                                                    <label class="group flex items-center gap-3 cursor-pointer">
                                                        <input
                                                            type="checkbox"
                                                            :value="val"
                                                            @change="toggleCheckbox(q.number, val)"
                                                            :checked="(answers[q.number] ?? []).includes(val)"
                                                            class="hidden peer"
                                                        >
                                                        <span
                                                            class="flex-1 px-4 md:px-5 py-3 rounded-lg border border-gray-200 text-base md:text-lg text-gray-900 font-medium
                                                                   peer-checked:bg-[#1f3b67] peer-checked:border-yellow-400 peer-checked:text-white
                                                                   group-hover:border-yellow-300 transition-all duration-150"
                                                            x-text="val"
                                                        ></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </template>

                                        {{-- Star Rating --}}
                                        <template x-if="q.type === 'bar'">
                                            <div class="py-4">
                                                <div class="flex flex-wrap gap-1 md:gap-2">
                                                    <template x-for="n in [1,2,3,4,5,6,7,8,9,10]" :key="n">
                                                        <button
                                                            type="button"
                                                            @click="answers[q.number] = n"
                                                            class="focus:outline-none transition-transform hover:scale-110 active:scale-95"
                                                            :title="`${n} out of 10`"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24"
                                                                class="w-7 h-7 md:w-8 md:h-8 transition-colors duration-150"
                                                                :class="(answers[q.number] ?? 0) >= n
                                                                    ? 'fill-[#1f3b67] stroke-[#1f3b67]'
                                                                    : 'fill-none stroke-gray-300 hover:stroke-yellow-300'"
                                                                stroke-width="1.5"
                                                                stroke-linejoin="round"
                                                                stroke-linecap="round"
                                                            >
                                                                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                                                            </svg>
                                                        </button>
                                                    </template>
                                                </div>
                                                <div class="mt-4 flex items-center gap-2">
                                                    <span
                                                        class="inline-block bg-gray-100 text-gray-800 text-xl font-bold px-4 py-1.5 rounded-lg"
                                                        x-text="(answers[q.number] ?? 0) + ' / 10'"
                                                    ></span>
                                                    <span
                                                        x-show="!answers[q.number]"
                                                        class="text-base text-gray-400 italic"
                                                    >Click a star to rate</span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- Footer --}}
                            <div class="flex items-center justify-between px-6 md:px-10 py-4 md:py-5 border-t border-gray-100 bg-gray-50">
                                <button
                                    type="button"
                                    @click="prev()"
                                    x-show="currentQuestion > 0"
                                    class="px-4 md:px-6 py-2.5 rounded-lg border border-gray-200 text-base md:text-lg font-medium text-gray-600 hover:bg-gray-100 transition"
                                >
                                    ← Previous
                                </button>
                                <div x-show="currentQuestion === 0" class="w-px"></div>

                                <div class="flex gap-3">
                                    <button
                                        type="button"
                                        @click="next()"
                                        x-show="data && currentQuestion < data.questions.length - 1"
                                        class="px-4 md:px-6 py-2.5 rounded-lg bg-[#1f3b67] text-white text-base md:text-lg font-medium hover:bg-gray-700 transition"
                                    >
                                        Next →
                                    </button>
                                    <button
                                        type="submit"
                                        x-show="data && currentQuestion === data.questions.length - 1"
                                        :disabled="submitting"
                                        class="px-4 md:px-6 py-2.5 rounded-lg bg-[#1f3b67] text-white text-base md:text-lg font-semibold hover:bg-[#1f3b67] transition disabled:opacity-60 disabled:cursor-not-allowed"
                                    >
                                        <span x-text="submitting ? 'Submitting...' : 'Submit Survey'"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Toast --}}
            <template x-teleport="body">
                <div
                    x-show="toast.visible"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="fixed bottom-5 right-5 z-[9999] px-6 py-4 rounded-lg shadow-lg text-white text-lg font-medium max-w-sm bg-[#1f3b67]"
                    x-text="toast.message"
                    style="display: none;"
                ></div>
            </template>
        </div>
    @endif

    <script>
        function Survey() {
            return {
                open: false,
                loading: false,
                submitting: false,
                data: null,
                currentQuestion: 0,
                answers: {},
                surveyId: null,
                userId: null,
                message: null,
                messageType: 'info',
                toast: { visible: false, message: '', type: 'info' },

                init() {
                    // Alpine handles reactivity — nothing extra needed
                },

                async load() {
                    if (this.data) return; // already loaded

                    this.loading = true;
                    this.message = null;

                    try {
                        const response = await fetch('{{ route("survey.data") }}', {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) throw new Error(data.message || 'Failed to load survey');

                        if (data.show_survey) {
                            this.data = data;
                            this.surveyId = data.survey.id;
                            this.userId = data.user_id;

                            // Seed default answers
                            data.questions.forEach(q => {
                                if (q.type === 'bar') this.answers[q.number] = 0; // stars start empty
                                if (q.type === 'checkbox') this.answers[q.number] = [];
                            });
                        } else {
                            this.message = data.message || 'No survey available at this time.';
                            this.messageType = 'info';
                        }
                    } catch (error) {
                        console.error('Survey load error:', error);
                        this.message = 'Unable to load survey. Please try again later.';
                        this.messageType = 'error';
                    } finally {
                        this.loading = false;
                    }
                },

                toggleCheckbox(questionNumber, val) {
                    if (!this.answers[questionNumber]) this.answers[questionNumber] = [];
                    const idx = this.answers[questionNumber].indexOf(val);
                    if (idx > -1) {
                        this.answers[questionNumber].splice(idx, 1);
                    } else {
                        this.answers[questionNumber].push(val);
                    }
                },

                validate() {
                    const q = this.data.questions[this.currentQuestion];
                    const answer = this.answers[q.number];

                    if (q.type === 'radio' && !answer) {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'warning',
                                title: 'Please select an answer before proceeding.'
                            }
                        }));
                        return false;
                    }

                    if (q.type === 'checkbox' && (!answer || answer.length === 0)) {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'warning',
                                title: 'Please select at least one option.'
                            }
                        }));
                        return false;
                    }

                    if (q.type === 'text' && (!answer || !answer.trim())) {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'warning',
                                title: 'Please provide an answer before proceeding.'
                            }
                        }));
                        return false;
                    }

                    if (q.type === 'bar' && (!answer || answer === 0)) {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'warning',
                                title: 'Please select a star rating before proceeding.'
                            }
                        }));
                        return false;
                    }

                    return true;
                },

                next() {
                    if (this.validate()) this.currentQuestion++;
                },

                prev() {
                    this.currentQuestion--;
                },

                async submit() {
                    if (!this.validate()) return;

                    const payload = {};
                    this.data.questions.forEach(q => {
                        payload[q.number] = {
                            question: q.question,
                            value: this.answers[q.number] ?? ''
                        };
                    });

                    this.submitting = true;

                    try {
                        const response = await fetch('{{ route("survey.submit") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                survey_id: this.surveyId,
                                answers: payload
                            })
                        });

                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message || 'Submission failed');

                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'success',
                                title: result.message
                            }
                        }));
                        setTimeout(() => window.location.reload(), 1500);

                    } catch (error) {
                        console.error('Survey submit error:', error);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                title: error.message || 'An error occurred. Please try again.'
                            }
                        }));
                    } finally {
                        this.submitting = false;
                    }
                },

                close(hideButton = false) {
                    this.open = false;
                    this.currentQuestion = 0;
                    this.submitting = false;

                    if (hideButton) {
                        this.$el.style.display = 'none';
                    }
                },
            };
        }
    </script>
@endauth
