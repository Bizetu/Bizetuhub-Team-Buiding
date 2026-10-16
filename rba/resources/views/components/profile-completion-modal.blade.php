@auth
    @if ($needsCompletion)
        <div
            x-data
            x-init="$nextTick(() => $dispatch('open-modal', 'profile-completion'))"
        ></div>

        <x-modal name="profile-completion" :show="false" maxWidth="lg" :closeable="false">

            <div class="bg-[#1F3B67] px-6 vp-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-white/10">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white leading-tight">
                            Complete Your Profile
                        </h2>
                        <p class="text-lg text-white mt-0.5">
                            Please fill in the missing details to continue.
                        </p>
                    </div>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('profile.complete') }}"
                x-data="{
                    loading: false,

                    companyId: {{ !in_array('company_id', $missingFields) && auth()->user()?->company_id
                                    ? (int) auth()->user()->company_id
                                    : (old('company_id') ? (int) old('company_id') : 'null') }},

                    branchOptions:       [],
                    loadingBranches:     false,
                    branchFetchError:    null,
                    branchOpen:          false,
                    branchSelectedValue: {{ old('branch_id') ? (int) old('branch_id') : 'null' }},
                    branchSelectedLabel: {{ Js::from('Select Branch') }},

                    departmentId: {{ old('department_id') ? (int) old('department_id') : 'null' }},

                    jobRoleOptions:       [],
                    loadingJobRoles:      false,
                    jobRoleFetchError:    null,
                    jobRoleOpen:          false,
                    jobRoleSelectedValue: {{ old('job_role_id') ? (int) old('job_role_id') : 'null' }},
                    jobRoleSelectedLabel: {{ Js::from('Select Job Role') }},

                    toast:       null,
                    _toastTimer: null,

                    init() {
                        // Pre-load branches when company is already known
                        if (this.companyId) {
                            this.fetchBranches(this.companyId);
                        }
                        // Pre-load job roles when department is already known (e.g. validation error)
                        if (this.departmentId) {
                            this.fetchJobRoles(this.departmentId);
                        }
                    },

                    async fetchBranches(id) {
                        this.loadingBranches  = true;
                        this.branchFetchError = null;
                        this.branchOptions    = [];
                        try {
                            const res = await fetch(
                                `{{ url('companyBranches') }}/${encodeURIComponent(id)}`,
                                { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }
                            );
                            if (!res.ok) {
                                const body = await res.json().catch(() => ({}));
                                throw new Error(body.message ?? `Server error (${res.status}). Please try again.`);
                            }
                            const data = await res.json();
                            if (!Array.isArray(data)) {
                                throw new Error('Unexpected response from server. Please reload and try again.');
                            }
                            this.branchOptions = data.map(b => ({ value: String(b.id), label: b.title }));
                            if (this.branchOptions.length === 0) {
                                this.branchFetchError = 'No branches are available for this company yet.';
                            }
                            // Restore a pre-selected value after a validation-error page reload
                            if (this.branchSelectedValue) {
                                const match = this.branchOptions.find(
                                    b => b.value === String(this.branchSelectedValue)
                                );
                                if (match) this.branchSelectedLabel = match.label;
                            }
                        } catch (e) {
                            this.branchFetchError = e.message || 'Could not load branches. Please try again.';
                            this.branchOptions    = [];
                        } finally {
                            this.loadingBranches = false;
                        }
                    },

                    tryOpenBranch() {
                        if (!this.companyId) {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'warning',
                                    title: 'Please select your bank first to view available branches.'
                                }
                            }));
                            return;
                        }
                        if (this.loadingBranches) return;
                        // Retry if a previous fetch errored out
                        if (this.branchOptions.length === 0 && this.branchFetchError) {
                            this.fetchBranches(this.companyId);
                            return;
                        }
                        this.branchOpen = !this.branchOpen;
                    },

                    chooseBranch(value, label) {
                        this.branchSelectedValue = value;
                        this.branchSelectedLabel = label;
                        this.branchOpen          = false;
                    },

                    isBranchSelected(value) {
                        return this.branchSelectedValue !== null
                            && String(this.branchSelectedValue) === String(value);
                    },

                    async fetchJobRoles(id) {
                        this.loadingJobRoles   = true;
                        this.jobRoleFetchError = null;
                        this.jobRoleOptions    = [];
                        try {
                            const res = await fetch(
                                `{{ url('departmentJobRoles') }}/${encodeURIComponent(id)}`,
                                { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }
                            );
                            if (!res.ok) {
                                const body = await res.json().catch(() => ({}));
                                throw new Error(body.message ?? `Server error (${res.status}). Please try again.`);
                            }
                            const data = await res.json();
                            if (!Array.isArray(data)) {
                                throw new Error('Unexpected response from server. Please reload and try again.');
                            }
                            this.jobRoleOptions = data.map(j => ({ value: String(j.id), label: j.title }));
                            if (this.jobRoleOptions.length === 0) {
                                this.jobRoleFetchError = 'No job roles are available for this department yet.';
                            }
                            // Restore a pre-selected value after a validation-error page reload
                            if (this.jobRoleSelectedValue) {
                                const match = this.jobRoleOptions.find(
                                    j => j.value === String(this.jobRoleSelectedValue)
                                );
                                if (match) this.jobRoleSelectedLabel = match.label;
                            }
                        } catch (e) {
                            this.jobRoleFetchError = e.message || 'Could not load job roles. Please try again.';
                            this.jobRoleOptions    = [];
                        } finally {
                            this.loadingJobRoles = false;
                        }
                    },

                    tryOpenJobRole() {
                        if (!this.departmentId) {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'warning',
                                    title: 'Please select your department first to view available job roles.'
                                }
                            }));
                            return;
                        }
                        if (this.loadingJobRoles) return;
                        // Retry on error
                        if (this.jobRoleOptions.length === 0 && this.jobRoleFetchError) {
                            this.fetchJobRoles(this.departmentId);
                            return;
                        }
                        this.jobRoleOpen = !this.jobRoleOpen;
                    },

                    chooseJobRole(value, label) {
                        this.jobRoleSelectedValue = value;
                        this.jobRoleSelectedLabel = label;
                        this.jobRoleOpen          = false;
                    },

                    isJobRoleSelected(value) {
                        return this.jobRoleSelectedValue !== null
                            && String(this.jobRoleSelectedValue) === String(value);
                    },
                }"
                x-on:submit="loading = true"
            >
                @csrf

                <div class="px-6 py-6 space-y-5">

                    <div
                        x-show="toast !== null"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="rounded-md bg-[#1F3B67]/10 border border-[#1F3B67] px-4 py-3 text-base text-[#1F3B67] flex items-center gap-2"
                        style="display:none"
                    >
                        <span x-text="toast"></span>
                    </div>

                    @if (session('profile_error'))
                        <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-lg text-red-700">
                            {{ session('profile_error') }}
                        </div>
                    @endif

                    @if (in_array('company_id', $missingFields))
                        <div
                            x-on:change="
                                const val = $el.querySelector('input[name=company_id]')?.value;
                                if (val && String(val) !== String(companyId)) {
                                    companyId           = val;
                                    branchSelectedValue = null;
                                    branchSelectedLabel = 'Select Branch';
                                    branchOpen          = false;
                                    branchFetchError    = null;
                                    fetchBranches(val);
                                }
                            "
                        >
                            <label for="company_id-btn" class="block text-lg font-medium text-gray-700 mb-1">
                                Bank <span class="text-red-500">*</span>
                            </label>
                            <x-select
                                id="company_id"
                                name="company_id"
                                placeholder="Select Bank"
                                :options="$companies->pluck('title', 'id')->toArray()"
                                :selected="old('company_id')"
                                required
                            />
                            @error('company_id')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if (in_array('branch_id', $missingFields))
                        <div>
                            <label for="branch_id-btn" class="block text-lg font-medium text-gray-700 mb-1">
                                Branch <span class="text-red-500">*</span>
                            </label>

                            <div
                                x-on:keydown.escape="branchOpen = false"
                                x-on:click.outside="branchOpen = false"
                                class="relative"
                            >
                                <input type="hidden" name="branch_id" :value="branchSelectedValue" required />

                                <button
                                    type="button"
                                    id="branch_id-btn"
                                    x-on:click="tryOpenBranch()"
                                    :aria-expanded="branchOpen"
                                    aria-haspopup="listbox"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-200 border
                                           border-transparent rounded-lg text-lg text-left cursor-pointer
                                           focus:outline-none focus:ring-2 focus:ring-[#1F3B67] focus:border-transparent
                                           transition-all duration-150"
                                    :class="loadingBranches ? 'opacity-60 cursor-not-allowed' : ''"
                                    :disabled="loadingBranches"
                                >
                                    <span
                                        :class="branchSelectedValue === null ? 'text-gray-400' : 'text-gray-700'"
                                        x-text="loadingBranches ? 'Loading branches…' : branchSelectedLabel"
                                    ></span>

                                    <svg
                                        x-show="loadingBranches"
                                        class="animate-spin w-4 h-4 text-gray-500 shrink-0 ml-2"
                                        fill="none" viewBox="0 0 24 24"
                                        style="display:none"
                                    >
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>

                                    <svg
                                        x-show="!loadingBranches"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 text-gray-500 transition-transform duration-200 shrink-0 ml-2"
                                        :class="branchOpen ? 'rotate-180' : 'rotate-0'"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <p
                                    x-show="branchFetchError"
                                    x-text="branchFetchError"
                                    class="mt-1 text-base text-[#1F3B67]"
                                    style="display:none"
                                ></p>

                                <div
                                    x-show="branchOpen"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                    role="listbox"
                                    class="absolute z-50 mt-1 w-full origin-top bg-white border border-gray-200
                                           rounded-lg shadow-lg overflow-hidden"
                                    style="display:none"
                                >
                                    <ul class="max-h-60 overflow-y-auto py-1 divide-y divide-gray-50">
                                        <template x-if="branchOptions.length === 0">
                                            <li class="px-4 py-2.5 text-lg text-gray-400 italic select-none">
                                                No branches found.
                                            </li>
                                        </template>
                                        <template x-for="option in branchOptions" :key="option.value">
                                            <li
                                                role="option"
                                                :aria-selected="isBranchSelected(option.value)"
                                                x-on:click="chooseBranch(option.value, option.label)"
                                                class="flex items-center justify-between px-4 py-2.5 text-lg cursor-pointer
                                                       text-gray-700 select-none hover:bg-gray-100 transition-colors duration-100"
                                                :class="isBranchSelected(option.value) ? 'font-medium text-gray-900 bg-gray-50' : ''"
                                            >
                                                <span x-text="option.label"></span>
                                                <svg
                                                    x-show="isBranchSelected(option.value)"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 shrink-0 ml-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                    style="color: #1F3B67;"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            @error('branch_id')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if (in_array('department_id', $missingFields))
                        <div
                            x-on:change="
                                const val = $el.querySelector('input[name=department_id]')?.value;
                                if (val && String(val) !== String(departmentId)) {
                                    departmentId          = val;
                                    jobRoleSelectedValue  = null;
                                    jobRoleSelectedLabel  = 'Select Job Role';
                                    jobRoleOpen           = false;
                                    jobRoleFetchError     = null;
                                    fetchJobRoles(val);
                                }
                            "
                        >
                            <label for="department_id-btn" class="block text-lg font-medium text-gray-700 mb-1">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <x-select
                                id="department_id"
                                name="department_id"
                                placeholder="Select Department"
                                :options="$departments->pluck('title', 'id')->toArray()"
                                :selected="old('department_id')"
                                required
                            />
                            @error('department_id')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if (in_array('job_role_id', $missingFields))
                        <div>
                            <label for="job_role_id-btn" class="block text-lg font-medium text-gray-700 mb-1">
                                Job Role <span class="text-red-500">*</span>
                            </label>

                            <div
                                x-on:keydown.escape="jobRoleOpen = false"
                                x-on:click.outside="jobRoleOpen = false"
                                class="relative"
                            >
                                <input type="hidden" name="job_role_id" :value="jobRoleSelectedValue" required />

                                <button
                                    type="button"
                                    id="job_role_id-btn"
                                    x-on:click="tryOpenJobRole()"
                                    :aria-expanded="jobRoleOpen"
                                    aria-haspopup="listbox"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-200 border
                                           border-transparent rounded-lg text-lg text-left cursor-pointer
                                           focus:outline-none focus:ring-2 focus:ring-[#1F3B67] focus:border-transparent
                                           transition-all duration-150"
                                    :class="loadingJobRoles ? 'opacity-60 cursor-not-allowed' : ''"
                                    :disabled="loadingJobRoles"
                                >
                                    <span
                                        :class="jobRoleSelectedValue === null ? 'text-gray-400' : 'text-gray-700'"
                                        x-text="loadingJobRoles ? 'Loading job roles…' : jobRoleSelectedLabel"
                                    ></span>

                                    <svg
                                        x-show="loadingJobRoles"
                                        class="animate-spin w-4 h-4 text-gray-500 shrink-0 ml-2"
                                        fill="none" viewBox="0 0 24 24"
                                        style="display:none"
                                    >
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>

                                    <svg
                                        x-show="!loadingJobRoles"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 text-gray-500 transition-transform duration-200 shrink-0 ml-2"
                                        :class="jobRoleOpen ? 'rotate-180' : 'rotate-0'"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <p
                                    x-show="jobRoleFetchError"
                                    x-text="jobRoleFetchError"
                                    class="mt-1 text-base text-[#1F3B67]"
                                    style="display:none"
                                ></p>

                                <div
                                    x-show="jobRoleOpen"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                    role="listbox"
                                    class="absolute z-50 mt-1 w-full origin-top bg-white border border-gray-200
                                           rounded-lg shadow-lg overflow-hidden"
                                    style="display:none"
                                >
                                    <ul class="max-h-60 overflow-y-auto py-1 divide-y divide-gray-50">
                                        <template x-if="jobRoleOptions.length === 0">
                                            <li class="px-4 py-2.5 text-lg text-gray-400 italic select-none">
                                                No job roles found.
                                            </li>
                                        </template>
                                        <template x-for="option in jobRoleOptions" :key="option.value">
                                            <li
                                                role="option"
                                                :aria-selected="isJobRoleSelected(option.value)"
                                                x-on:click="chooseJobRole(option.value, option.label)"
                                                class="flex items-center justify-between px-4 py-2.5 text-lg cursor-pointer
                                                       text-gray-700 select-none hover:bg-gray-100 transition-colors duration-100"
                                                :class="isJobRoleSelected(option.value) ? 'font-medium text-gray-900 bg-gray-50' : ''"
                                            >
                                                <span x-text="option.label"></span>
                                                <svg
                                                    x-show="isJobRoleSelected(option.value)"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 shrink-0 ml-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                    style="color: #1F3B67;"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            @error('job_role_id')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @if (in_array('gender_id', $missingFields))
                        <div>
                            <label for="gender_id-btn" class="block text-lg font-medium text-gray-700 mb-1">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <x-select
                                id="gender_id"
                                name="gender_id"
                                placeholder="I am a..."
                                :options="$genders->pluck('title', 'id')->toArray()"
                                :selected="old('gender_id')"
                                required
                            />
                            @error('gender_id')
                                <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="phone_number" class="block text-lg font-medium text-gray-700 mb-1">
                            Phone <span class="text-red-500">*</span>
                        </label>
                        <x-input
                            label="Phone"
                            id="phone_number"
                            name="phone_number"
                            type="tel"
                            placeholder="+250•••••••••"
                            :value="auth()->user()->phone_number ?? null"
                        />
                        @error('phone_number')
                            <p class="mt-1 text-base text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-3 flex items-center justify-between gap-2">
                    <p class="flex items-start text-lg text-gray-500 w-full">
                        <input
                            type="checkbox"
                            name="policy_agree"
                            id="policy_agree"
                            value="1"
                            required
                            class="accent-[#1F3B67] w-4 h-4 mt-1.5 border-2 border-gray-300 rounded focus:ring-0 focus:ring-offset-0 text-black flex-shrink-0"
                        >
                        <span class="ml-3">
                            I agree to Rwanda Bankers' Association's
                            <a href="/privacy-policy" class="underline font-semibold text-black hover:text-gray-600">Privacy Policy</a>
                            and
                            <a href="/terms-conditions" class="underline font-semibold text-black hover:text-gray-600">Terms of Use</a>
                        </span>
                        @error('policy_agree')
                            <p class="text-lg text-red-600">{{ $message }}</p>
                        @enderror
                    </p>
                </div>
                <div class="px-6 pb-4 flex items-center justify-between gap-2">
                    <button
                        type="submit"
                        x-bind:disabled="loading"
                        class="inline-flex items-center gap-1 rounded-md bg-[#1F3B67] px-4 py-2 text-lg font-semibold text-white shadow-sm mx-auto hover:bg-[#1F3B67] focus:outline-none focus:ring-2 focus:ring-[#1F3B67] focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-150"
                    >
                        <svg x-show="loading" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="loading ? 'Saving…' : 'Save & Continue'"></span>
                    </button>
                </div>
            </form>

        </x-modal>

        @if ($errors->any())
            <div x-data x-init="$nextTick(() => $dispatch('open-modal', 'profile-completion'))"></div>
        @endif

    @endif
@endauth
