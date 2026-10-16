@extends('layouts.app')

@section('title', 'Edit Profile - Rwanda Bankers Association')

@section('content')

    @section('hero_image', asset('images/defaults/banner.webp'))

    <x-hero
        title="{{ $pageItem->header_title }}"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $pageItem->header_title, 'url' => $pageItem->key],
        ]"
        :backgroundImage="$pageItem->image('hero_image')"
        height="h-80"
    />

    <div class="my-10 px-4">
        <div class="max-w-7xl mx-auto">

            <div class="bg-gray-100 shadow-lg rounded-xl overflow-hidden flex flex-col md:flex-row">
                <div class="flex flex-col items-center justify-start py-10 px-8 gap-6 text-white md:w-72 w-full shrink-0"
                     style="background-color: #1F3B67;">

                    <div class="w-40 h-40 rounded-full overflow-hidden shadow-lg">
                        <img
                            id="profile-preview"
                            src="{{ auth()->user()->profile_pic_url }}"
                            alt="Profile Photo"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <div class="text-center">
                        <p class="text-xl text-white font-semibold leading-tight">
                            {{ auth()->user()->name_value }}
                        </p>
                        <p class="text-base text-white opacity-80 mt-1">
                            {{ auth()->user()->email }}
                        </p>
                        <p class="text-base text-white opacity-80 mt-1">
                            {{ auth()->user()->company_name_value }}
                        </p>
                    </div>

                    <x-secondary-button href="{{ route('profile.progress') }}">
                        <x-slot name="icon">
                            <x-my-course-progress />
                        </x-slot>
                        <span class="text-base">My Course Progress</span>
                    </x-secondary-button>

                </div>

                <div class="flex-1 p-8 md:p-12">

                    <div class="border-b border-gray-200 pb-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Personal Information</h2>
                        <p class="text-base text-gray-500 mt-1">Edit your details and click submit to finish the process.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <ul class="list-disc list-inside text-base text-red-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-input
                                    label="First Name"
                                    id="first_name"
                                    name="first_name"
                                    placeholder="Enter your first name"
                                    :value="old('first_name', auth()->user()->first_name ?? '')"
                                    required autofocus autocomplete="given-name"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                            </div>
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-input
                                    label="Last Name"
                                    id="last_name"
                                    name="last_name"
                                    placeholder="Enter your last name"
                                    :value="old('last_name', auth()->user()->last_name ?? '')"
                                    required autocomplete="family-name"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-6"
                            x-data="{
                                companyId:           {{ Js::from(old('company_id', auth()->user()->company_id ?? null)) }},

                                branchOptions:       [],
                                loadingBranches:     false,
                                branchFetchError:    null,
                                branchOpen:          false,
                                branchSelectedValue: {{ Js::from(old('branch_id', auth()->user()->branch_id ?? null)) }},
                                branchSelectedLabel: {{ Js::from('What branch do you belong to?') }},

                                toast:       null,
                                _toastTimer: null,

                                init() {
                                    if (this.companyId) this.fetchBranches(this.companyId);
                                },

                                showToast(msg) {
                                    this.toast = msg;
                                    clearTimeout(this._toastTimer);
                                    this._toastTimer = setTimeout(() => { this.toast = null; }, 3500);
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
                                        // Restore label after validation-error reload
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
                                        this.showToast('Please select a bank first to view available branches.');
                                        return;
                                    }
                                    if (this.loadingBranches) return;
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
                            }"
                            x-on:change="
                                const val = $el.querySelector('input[name=company_id]')?.value;
                                if (val && String(val) !== String(companyId)) {
                                    companyId           = val;
                                    branchSelectedValue = null;
                                    branchSelectedLabel = 'What branch do you belong to?';
                                    branchOpen          = false;
                                    branchFetchError    = null;
                                    fetchBranches(val);
                                }
                            "
                        >
                            <div
                                class="col-span-full"
                                x-show="toast !== null"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                style="display:none"
                            >
                                <div class="rounded-md bg-amber-50 border border-amber-200 px-4 py-3 text-base text-amber-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="toast"></span>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="company_id" :value="__('Bank')" />
                                <x-select
                                    id="company_id"
                                    name="company_id"
                                    placeholder="Which bank do you belong to?"
                                    :options="$companies"
                                    :selected="old('company_id', auth()->user()->company_id ?? null)"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('company_id')" />
                            </div>

                            <div>
                                <x-input-label for="branch_id" :value="__('Branch')" />

                                <div
                                    x-on:keydown.escape="branchOpen = false"
                                    x-on:click.outside="branchOpen = false"
                                    class="relative"
                                >
                                    <input type="hidden" name="branch_id" :value="branchSelectedValue" />

                                    <button
                                        type="button"
                                        id="branch_id-btn"
                                        x-on:click="tryOpenBranch()"
                                        :aria-expanded="branchOpen"
                                        aria-haspopup="listbox"
                                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-200 border
                                               border-transparent rounded-lg text-lg text-left cursor-pointer
                                               focus:outline-none focus:ring-2 focus:ring-[#FFB901] focus:border-transparent
                                               transition-all duration-150"
                                        :class="loadingBranches ? 'opacity-60 cursor-not-allowed' : ''"
                                        :disabled="loadingBranches"
                                    >
                                        <span
                                            :class="branchSelectedValue === null ? 'text-gray-400' : 'text-gray-700'"
                                            x-text="loadingBranches ? 'Loading branches…' : branchSelectedLabel"
                                        ></span>
                                        <svg x-show="loadingBranches" class="animate-spin w-4 h-4 text-gray-500 shrink-0 ml-2"
                                             fill="none" viewBox="0 0 24 24" style="display:none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                        <svg x-show="!loadingBranches"
                                             xmlns="http://www.w3.org/2000/svg"
                                             class="w-4 h-4 text-gray-500 transition-transform duration-200 shrink-0 ml-2"
                                             :class="branchOpen ? 'rotate-180' : 'rotate-0'"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <p x-show="branchFetchError" x-text="branchFetchError"
                                       class="mt-1 text-base text-amber-600" style="display:none"></p>

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
                                                <li class="px-4 py-2.5 text-lg text-gray-400 italic select-none">No branches found.</li>
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
                                                    <svg x-show="isBranchSelected(option.value)"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         class="w-4 h-4 shrink-0 ml-2" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                         style="color: #FFB901;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <x-input-error class="mt-2" :messages="$errors->get('branch_id')" />
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-6"
                            x-data="{
                                departmentId:         {{ Js::from(old('department_id', auth()->user()->department_id ?? null)) }},

                                jobRoleOptions:       [],
                                loadingJobRoles:      false,
                                jobRoleFetchError:    null,
                                jobRoleOpen:          false,
                                jobRoleSelectedValue: {{ Js::from(old('job_role_id', auth()->user()->job_role_id ?? null)) }},
                                jobRoleSelectedLabel: {{ Js::from('What is your job role?') }},

                                toast:       null,
                                _toastTimer: null,

                                init() {
                                    if (this.departmentId) this.fetchJobRoles(this.departmentId);
                                },

                                showToast(msg) {
                                    this.toast = msg;
                                    clearTimeout(this._toastTimer);
                                    this._toastTimer = setTimeout(() => { this.toast = null; }, 3500);
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
                                        // Restore label after validation-error reload
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
                                        this.showToast('Please select a department first to view available job roles.');
                                        return;
                                    }
                                    if (this.loadingJobRoles) return;
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
                            x-on:change="
                                const val = $el.querySelector('input[name=department_id]')?.value;
                                if (val && String(val) !== String(departmentId)) {
                                    departmentId          = val;
                                    jobRoleSelectedValue  = null;
                                    jobRoleSelectedLabel  = 'What is your job role?';
                                    jobRoleOpen           = false;
                                    jobRoleFetchError     = null;
                                    fetchJobRoles(val);
                                }
                            "
                        >
                            <div
                                class="col-span-full"
                                x-show="toast !== null"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                style="display:none"
                            >
                                <div class="rounded-md bg-amber-50 border border-amber-200 px-4 py-3 text-base text-amber-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="toast"></span>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="department_id" :value="__('Department')" />
                                <x-select
                                    id="department_id"
                                    name="department_id"
                                    placeholder="What is your department?"
                                    :options="$departments"
                                    :selected="old('department_id', auth()->user()->department_id ?? null)"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                            </div>

                            <div>
                                <x-input-label for="job_role_id" :value="__('Job Role')" />

                                <div
                                    x-on:keydown.escape="jobRoleOpen = false"
                                    x-on:click.outside="jobRoleOpen = false"
                                    class="relative"
                                >
                                    <input type="hidden" name="job_role_id" :value="jobRoleSelectedValue" />

                                    <button
                                        type="button"
                                        id="job_role_id-btn"
                                        x-on:click="tryOpenJobRole()"
                                        :aria-expanded="jobRoleOpen"
                                        aria-haspopup="listbox"
                                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-200 border
                                               border-transparent rounded-lg text-lg text-left cursor-pointer
                                               focus:outline-none focus:ring-2 focus:ring-[#FFB901] focus:border-transparent
                                               transition-all duration-150"
                                        :class="loadingJobRoles ? 'opacity-60 cursor-not-allowed' : ''"
                                        :disabled="loadingJobRoles"
                                    >
                                        <span
                                            :class="jobRoleSelectedValue === null ? 'text-gray-400' : 'text-gray-700'"
                                            x-text="loadingJobRoles ? 'Loading job roles…' : jobRoleSelectedLabel"
                                        ></span>
                                        <svg x-show="loadingJobRoles" class="animate-spin w-4 h-4 text-gray-500 shrink-0 ml-2"
                                             fill="none" viewBox="0 0 24 24" style="display:none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                        <svg x-show="!loadingJobRoles"
                                             xmlns="http://www.w3.org/2000/svg"
                                             class="w-4 h-4 text-gray-500 transition-transform duration-200 shrink-0 ml-2"
                                             :class="jobRoleOpen ? 'rotate-180' : 'rotate-0'"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <p x-show="jobRoleFetchError" x-text="jobRoleFetchError"
                                       class="mt-1 text-base text-amber-600" style="display:none"></p>

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
                                                <li class="px-4 py-2.5 text-lg text-gray-400 italic select-none">No job roles found.</li>
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
                                                    <svg x-show="isJobRoleSelected(option.value)"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         class="w-4 h-4 shrink-0 ml-2" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                                                         style="color: #FFB901;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <x-input-error class="mt-2" :messages="$errors->get('job_role_id')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="gender_id" :value="__('Gender')" />
                                <x-select
                                    id="gender_id"
                                    name="gender_id"
                                    placeholder="Enter your gender"
                                    :options="$genders"
                                    :selected="old('gender_id', auth()->user()->gender_id ?? null)"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('gender_id')" />
                            </div>
                            <div>
                                <x-input-label for="phone_number" :value="__('Phone')" />
                                <x-input
                                    label="Phone"
                                    id="phone_number"
                                    name="phone_number"
                                    type="tel"
                                    placeholder="+250•••••••••"
                                    :value="old('phone_number', auth()->user()->phone_number ?? null)"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-base font-medium text-gray-700 mb-1">
                                Upload Profile Photo
                            </label>
                            <div class="flex items-center rounded-lg overflow-hidden border border-gray-200 w-full sm:w-auto">
                                <label
                                    for="profile_pic"
                                    id="file-label"
                                    class="flex-1 px-4 py-3 bg-gray-200 text-base text-gray-400 cursor-pointer truncate"
                                >
                                    No file selected
                                </label>
                                <x-secondary-button
                                    tag="label"
                                    for="profile_pic"
                                    class="rounded-none shadow-none px-6 py-3"
                                >
                                    Upload
                                </x-secondary-button>
                                <input
                                    type="file"
                                    id="profile_pic"
                                    name="profile_pic"
                                    accept="image/*"
                                    class="hidden"
                                />
                            </div>
                        </div>

                        <div class="pt-4">
                            <x-secondary-button
                                type="submit"
                                class="w-full sm:w-2/3 mx-auto block rounded-lg py-3.5 text-lg shadow"
                            >
                                Submit
                            </x-secondary-button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        const fileInput = document.getElementById('profile_pic');
        const fileLabel = document.getElementById('file-label');
        const preview   = document.getElementById('profile-preview');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            fileLabel.textContent = file.name;
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; };
            reader.readAsDataURL(file);
        });
    </script>

@endsection
