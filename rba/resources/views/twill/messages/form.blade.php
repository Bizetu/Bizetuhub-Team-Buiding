@extends('layouts.twill.jobrole_form')

@php
    $customForm = true;

    // $targets = DB::table('targets')->get();
    // dd($targets);
    $minDateTime = now()->addMinutes(10)->format('Y-m-d\TH:i');
    // dd(\App\Models\JobRole::all());

    $sendDate = $role->send_date;
    if (!$sendDate && !empty($item->send_date)) {
        $sendDate = \Carbon\Carbon::parse($item->send_date)->format('Y-m-d\TH:i');
    }
    // dd($item);

    $user = auth()->user();
    $isCompanyHr = $user->role == 'COMPANYHR';
    $isAdmin = $user->role == 'SUPERADMIN';
    $userCompanyId = $item->company_id ?? null;
    // dd($item);

    // dd(auth()->user()->company_id);
    // dd($item->company_id);
@endphp


@push('extra_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/multiselect_css/style.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/jquery-datetimepicker@2.5.20/build/jquery.datetimepicker.min.css" />

    <style>
        .filter-select {
            height: 43px !important;
            margin-bottom: 2rem;
            width: 100%;
            border: 1px solid rgba(60, 60, 60, .26);
        }

        textarea.form-control {
            min-height: 200px !important;
            /* Adjust as needed */
        }

        .input {
            margin-top: 35px;
            position: relative;
        }

        /* Custom Multi Select */
        .custom-select-wrapper {
            position: relative;
            width: 100%;
            font-size: 13px;
            user-select: none;
        }

        .custom-select-trigger {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 4px;
            padding-left: 12px;
            border: 1px solid #dee2e6;
            background: #fff;
            cursor: pointer;
            min-height: 43px;
        }

        .custom-select-trigger:hover {
            border-color: #aaa;
        }

        .custom-select-trigger.open {
            border-color: #ced4da;
        }

        .select-arrow {
            font-size: 20px;
            transition: transform 0.2s;
        }

        .custom-select-trigger.open .select-arrow {
            transform: rotate(180deg);
        }

        .select-placeholder {
            color: #000 !important;
            font-size: 13px;
        }

        .select-placeholder.has-selection {
            color: #212529;
            font-weight: 500;
        }

        .custom-select-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            background: #fbfbfb;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 999;
            max-height: 250px;
            overflow-y: auto;
        }

        .custom-select-dropdown.open {
            display: block;
        }

        .custom-select-search {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f3f5;
            position: sticky;
            top: 0;
            background: #fbfbfb;
            z-index: 1;
        }

        .custom-select-search input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            outline: none;
            font-size: 13px;
            box-sizing: border-box;
        }

        .custom-select-options {
            list-style: none;
            margin: 0;
            padding: 4px 0;
        }

        .custom-select-options li {
            padding: 9px 12px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #212529;
            transition: background 0.15s;
        }

        .custom-select-options li:hover {
            background: #f3f4f7;
        }

        .custom-select-options li.selected {
            background: #f0faf4;
            color: #1F3B67;
            font-weight: 500;
        }

        .custom-select-options li.selected::after {
            content: '✔';
            color: #1F3B67;
            font-weight: bold;
            flex-shrink: 0;
        }

        .custom-select-options li.hidden {
            display: none;
        }

        .form-control {
            border: 1px solid #ccc;
            /* adjust as needed */
            box-sizing: border-box;
            outline: 0;
            padding: .75rem;
            width: 100%;
            position: relative;
            cursor: pointer;
        }

        /* Make native date/time picker icon clickable */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: 100%;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: 100%;
        }

        /* Optional: remove default arrow in Firefox */
        input[type="date"]::-moz-calendar-picker-indicator,
        input[type="datetime-local"]::-moz-calendar-picker-indicator {
            cursor: pointer;
        }

        .special-padding {
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .bottom-padding {
            padding-bottom: 15px;
        }

        .top-padding {
            padding-top: 15px;
        }
    </style>
@endpush
@section('contentFields')
    <input type="hidden" name="role_id" value="{{ $role->id }}" />
    <input type="hidden" name="title" value="{{ $item->title }}" />

    <div class="col-12 no-padding-right top-padding">

        <div class="row">
            <div class="col-2 mt-4 mb-4 ">
                <label class="filter_by" style="color:#000">Company</label>
            </div>
            <div class="col-4" style="margin-top:5px">
                <select name="company_id" id="company_id" class="form-control mt-4 mb-4 filter-select"
                    {{ $isCompanyHr ? 'disabled' : '' }}>
                    <option value="" disabled
                        {{ empty(old('company_id', $item->company_id ?? '')) ? 'selected' : '' }}>
                        Select Company
                    </option>

                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id', $item->company_id ?? ($isCompanyHr ? $userCompanyId : null)) == $company->id ? 'selected' : '' }}>
                            {{ $company->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

            {{-- Branch Select --}}
        <div class="col-12 no-padding-right bottom-padding">
            <div class="row">
                <div class="col-2 mt-4 mb-4">
                    <label class="filter_by" style="color:#000">Select Branch</label>
                </div>
                <div class="col-4" style="margin-top:5px">
                    <div class="custom-select-wrapper" id="branch_select">
                        <div class="custom-select-trigger">
                            <span class="select-placeholder" data-label="Branch">Select Branches</span>
                            <span class="select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#000" viewBox="0 0 16 16"
                                    width="10" height="10">
                                    <path d="M8 13.1l-8-8 2.1-2.2 5.9 5.9 5.9-5.9 2.1 2.2z" />
                                </svg>
                            </span>
                        </div>
                        <div class="custom-select-dropdown">
                            <div class="custom-select-search">
                                <input type="text" placeholder="Search branches...">
                            </div>
                            <ul class="custom-select-options">
                                @foreach ($branches as $branch)
                                    <li data-value="{{ $branch->id }}"
                                        {{ in_array($branch->id, $selectedBranchList) ? 'class=selected' : '' }}>
                                        {{ $branch->title }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @foreach ($selectedBranchList as $id)
                            <input type="hidden" name="branch_ids[]" value="{{ $id }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Department Select --}}
        <div class="col-12 no-padding-right special-padding">
            <div class="row">
                <div class="col-2 mt-4 mb-4">
                    <label class="filter_by" style="color:#000">Select Department</label>
                </div>
                <div class="col-4" style="margin-top:5px">
                    <div class="custom-select-wrapper" id="department_select">
                        <div class="custom-select-trigger">
                            <span class="select-placeholder" data-label="Department">Select Departments</span>
                            <span class="select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#000" viewBox="0 0 16 16" width="10"
                                    height="10">
                                    <path d="M8 13.1l-8-8 2.1-2.2 5.9 5.9 5.9-5.9 2.1 2.2z" />
                                </svg>
                            </span>
                        </div>
                        <div class="custom-select-dropdown">
                            <div class="custom-select-search">
                                <input type="text" placeholder="Search departments...">
                            </div>
                            <ul class="custom-select-options">
                                @foreach ($departments as $department)
                                    <li data-value="{{ $department->id }}"
                                        {{ in_array($department->id, $selectedDepartmentList) ? 'class=selected' : '' }}>
                                        {{ $department->title }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        {{-- Pre-select hidden inputs --}}
                        @foreach ($selectedDepartmentList as $id)
                            <input type="hidden" name="department_ids[]" value="{{ $id }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Job Role Select --}}
        <div class="col-12 no-padding-right special-padding">
            <div class="row bottom-padding">
                <div class="col-2 mt-4 mb-4">
                    <label class="filter_by" style="color:#000">Select Job Role</label>
                </div>
                <div class="col-4" style="margin-top:5px">
                    <div class="custom-select-wrapper" id="job_role_select">
                        <div class="custom-select-trigger">
                            <span class="select-placeholder" data-label="Job Role">Select Job Roles</span>
                            <span class="select-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#000" viewBox="0 0 16 16" width="10"
                                    height="10">
                                    <path d="M8 13.1l-8-8 2.1-2.2 5.9 5.9 5.9-5.9 2.1 2.2z" />
                                </svg>
                            </span>
                        </div>
                        <div class="custom-select-dropdown">
                            <div class="custom-select-search">
                                <input type="text" placeholder="Search job roles...">
                            </div>
                            <ul class="custom-select-options">
                                @foreach ($jobroles as $jobrole)
                                    <li data-value="{{ $jobrole->id }}"
                                        {{ in_array($jobrole->id, $selectedJobRoleList) ? 'class=selected' : '' }}>
                                        {{ $jobrole->title }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @foreach ($selectedJobRoleList as $id)
                            <input type="hidden" name="job_role_ids[]" value="{{ $id }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Message types --}}
        <div class="row">
            <div class="col-2 mt-4 mb-4 ">
                <label class="filter_by" style="color:#000">Message Type</label>
            </div>
            <div class="col-4">
                <select name="message_type" id="message_type" class="form-control mt-4 mb-4 filter-select">

                    <option value="" disabled
                        {{ old('message_type', $item->message_type ?? null) ? '' : 'selected' }}>
                        Select Message Type
                    </option>

                    @foreach ($messageTypeList as $key => $value)
                        <option value="{{ $key }}"
                            {{ old('message_type', $item->message_type ?? null) == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach

                </select>

            </div>
        </div>

        {{-- Course Select --}}
        <div class="filter-block" data-block="course">
            <div class="col-12 no-padding-right special-padding">
                <div class="row">
                    <div class="col-2 mt-4 mb-4">
                        <label class="filter_by" style="color:#000">Select Course(s)</label>
                    </div>
                    <div class="col-4" style="margin-top:5px">
                        <div class="custom-select-wrapper" id="course_select">
                            <div class="custom-select-trigger">
                                <span class="select-placeholder" data-label="Course">Select Courses</span>
                                <span class="select-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#000" viewBox="0 0 16 16"
                                        width="10" height="10">
                                        <path d="M8 13.1l-8-8 2.1-2.2 5.9 5.9 5.9-5.9 2.1 2.2z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="custom-select-dropdown">
                                <div class="custom-select-search">
                                    <input type="text" placeholder="Search courses...">
                                </div>
                                <ul class="custom-select-options">
                                    @foreach ($courses as $course)
                                        <li data-value="{{ $course->id }}"
                                            {{ in_array($course->id, $selectedCourseList) ? 'class=selected' : '' }}>
                                            {{ $course->display_name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @foreach ($selectedCourseList as $id)
                                <input type="hidden" name="course_ids[]" value="{{ $id }}">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 no-padding-right bottom-padding">
        <div class="row">
            <div class="col-2 mt-4">
                <label class="filter_by" style="color:#000">Target Audience</label>
            </div>
            <div class="col-4" style="margin-top:5px">
                <select name="audience_ids[]" id="audience_ids" class="form-control mt-4 mb-4 filter-select">

                    <option value="" disabled {{ old('audience_ids') ? '' : 'selected' }}>
                        Select Target Learners
                    </option>

                    @foreach ($learnersCohorts as $key => $value)
                        <option value="{{ $key }}"
                            {{ old('audience_ids', $selectedAudienceList ?? null) == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach

                </select>

            </div>
        </div>
        </div>
        {{-- Subject --}}
        {{-- <div class="col-12 no-padding-right">
            <div class="row">
                <div class="col-2 mt-4 mb-4">
                    <label for="subject" class="filter_by" style="color:#000">Subject</label>
                </div>
                <div class="col-4" style="margin-top:5px">
                    <input type="text" name="subject" id="subject" class="form-control mt-4 mb-4 filter-select"
                        maxlength="100" value="{{ old('subject', $role->subject ?? '') }}" placeholder="Your Subject"
                        required>
                </div>
            </div>
        </div> --}}

        {{-- Message --}}
        {{-- <div class="col-12 no-padding-right">
            <div class="row">
                <div class="col-2 mt-4 mb-4">
                    <label for="message" class="filter_by" style="color:#000">Body</label>
                </div>
                <div class="col-8" style="margin-top:5px">
                    <textarea name="message" id="message" class="form-control mt-4 mb-4 filter-select" rows="6"
                        maxlength="5000" placeholder="Type your message here!" required>{{ old('message', $role->message ?? '') }}</textarea>
                </div>
            </div>
        </div> --}}

        {{-- Message Type --}}
        <div class="col-12 no-padding-right">
            <div class="row">
                <div class="col-2 mt-4 mb-4">
                    <label for="is_recurrent" class="filter_by" style="color:#000">Message Frequency</label>
                </div>
                <div class="col-4" style="margin-top:5px">
                    <select name="is_recurrent" id="is_recurrent" class="form-control mt-4 mb-4 filter-select">
                        <option value="" disabled
                            {{ old('is_recurrent', $role->is_recurrent ?? '') === null ? 'selected' : '' }}>
                            Select Message Type</option>
                        <option value="0"
                            {{ old('is_recurrent', $role->is_recurrent ?? '') == 0 ? 'selected' : '' }}>One Time
                        </option>
                        <option value="1"
                            {{ old('is_recurrent', $role->is_recurrent ?? '') == 1 ? 'selected' : '' }}>Recurrent
                        </option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Recurrent Frequency --}}
        <div class="col-12 no-padding-right recurrent-fields" style="display:none;">
            <div class="row">
                <div class="col-2 mt-4 mb-4">
                    <label for="recurrent_frequency" class="filter_by" style="color:#000">Recurrent Frequency</label>
                </div>
                <div class="col-4" style="margin-top:5px">
                    <select name="recurrent_frequency" id="recurrent_frequency"
                        class="form-control mt-4 mb-4 filter-select">
                        <option value="" disabled>Select Frequency</option>
                        <option value="7"
                            {{ old('recurrent_frequency', $role->recurrent_frequency ?? '') == 7 ? 'selected' : '' }}>One
                            Week</option>
                        <option value="14"
                            {{ old('recurrent_frequency', $role->recurrent_frequency ?? '') == 14 ? 'selected' : '' }}>Two
                            Weeks</option>
                        <option value="21"
                            {{ old('recurrent_frequency', $role->recurrent_frequency ?? '') == 21 ? 'selected' : '' }}>
                            Three Weeks</option>
                        <option value="28"
                            {{ old('recurrent_frequency', $role->recurrent_frequency ?? '') == 28 ? 'selected' : '' }}>Four
                            Weeks</option>
                        {{-- <option value="30"
                            {{ old('recurrent_frequency', $role->recurrent_frequency ?? '') == 30 ? 'selected' : '' }}>
                            Monthly</option> --}}
                    </select>
                </div>
            </div>
        </div>

        {{-- Send Date --}}
        <div class="col-12 no-padding-right">
            <div class="row clickable-input-wrapper" style="cursor: pointer;">
                <div class="col-2 mt-4 mb-4">
                    <label for="send_date" class="filter_by" style="color:#000">Send Date</label>
                </div>
                <div class="col-4" style="margin-top:5px">
                    <input type="datetime-local" name="send_date" id="send_date"
                        class="form-control mt-4 mb-4 filter-select" value="{{ $sendDate }}" required>
                </div>
            </div>
        </div>


    @stop

    @push('extra_js')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script src="{{ asset('assets/admin/multiselect_js/popper.js') }}"></script>
        <script src="{{ asset('assets/admin/multiselect_js/bootstrap.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        <script type="text/javascript">
            document.querySelector('form').addEventListener('submit', function(e) {
                const select = document.getElementById('audience_ids');
                const message = document.getElementById('message_type');

                if (!message.value) {
                    e.preventDefault(); // stop form submission
                    alert('Please Select Message Type');
                    message.focus();
                } else if (!select.value) {
                    e.preventDefault(); // stop form submission
                    alert('Please select Target Learners');
                    select.focus();
                }
            });

            $(document).ready(function() {
                const blockMap = {
                    '1': 'adminstrative_role',
                    '2': 'course',
                };

                function toggleBlocks() {
                    let selected = $('#message_type').val();
                    // learners-block
                    if (blockMap[selected] == 'individual' || blockMap[selected] == 'due_date') {
                        $('.learners-block').hide();
                    } else {
                        $('.learners-block').show();
                    }
                    $('.filter-block').hide();

                    if (selected && blockMap[selected]) {
                        $('.filter-block[data-block="' + blockMap[selected] + '"]').show();
                    }
                }

                toggleBlocks();
                $('#message_type').on('change', toggleBlocks);


                $('#is_recurrent').on('change', function() {
                    let choice = $(this).val();
                    let $recurrentFields = $('.recurrent-fields');
                    let $frequencySelect = $('#recurrent_frequency');

                    if (choice == 1) {
                        $recurrentFields.show();
                    } else {
                        // hide and clear the value so it doesn't persist
                        $recurrentFields.hide();
                        $frequencySelect.val('');
                    }
                });

                // trigger initial state
                $('#is_recurrent').trigger('change');


                // -----------------------------
                const sendDateInput = document.getElementById('send_date');

                // Helper to pad numbers
                function pad(num) {
                    return num.toString().padStart(2, '0');
                }

                // Set default value to 10 minutes from now (local time) if empty
                if (!sendDateInput.value) {
                    const defaultTime = new Date();
                    defaultTime.setMinutes(defaultTime.getMinutes() + 10);

                    const year = defaultTime.getFullYear();
                    const month = pad(defaultTime.getMonth() + 1); // months are 0-based
                    const day = pad(defaultTime.getDate());
                    const hours = pad(defaultTime.getHours());
                    const minutes = pad(defaultTime.getMinutes());

                    sendDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                }

                // Function to update the min attribute dynamically
                function updateMinTime() {
                    const minTime = new Date();
                    minTime.setMinutes(minTime.getMinutes() + 10);

                    const year = minTime.getFullYear();
                    const month = pad(minTime.getMonth() + 1);
                    const day = pad(minTime.getDate());
                    const hours = pad(minTime.getHours());
                    const minutes = pad(minTime.getMinutes());

                    sendDateInput.min = `${year}-${month}-${day}T${hours}:${minutes}`;
                }

                // Initialize min time
                updateMinTime();

                // Optional: keep updating min every minute if form stays open
                setInterval(updateMinTime, 60000);

                // Validate input whenever user changes it
                sendDateInput.addEventListener('input', function() {
                    const selected = new Date(this.value);
                    const minAllowed = new Date();
                    minAllowed.setMinutes(minAllowed.getMinutes() + 10);

                    if (selected < minAllowed) {
                        alert('Please select a date/time at least 10 minutes from now.');
                        // Optionally set to minimum allowed instead of blank
                        const year = minAllowed.getFullYear();
                        const month = pad(minAllowed.getMonth() + 1);
                        const day = pad(minAllowed.getDate());
                        const hours = pad(minAllowed.getHours());
                        const minutes = pad(minAllowed.getMinutes());

                        this.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                    }
                });

            });
        </script>



        <script>
            // =====================
            // Custom Multi Select
            // =====================
            function initCustomSelect(wrapperId, inputName, placeholder) {
                const $wrapper = $('#' + wrapperId);
                const $trigger = $wrapper.find('.custom-select-trigger');
                const $dropdown = $wrapper.find('.custom-select-dropdown');
                const $search = $wrapper.find('.custom-select-search input');
                const $placeholder = $wrapper.find('.select-placeholder');

                // Set initial count if items are pre-selected
                updateTriggerText($wrapper, $placeholder, placeholder);

                // Toggle open/close
                $trigger.on('click', function(e) {
                    e.stopPropagation();
                    const isOpen = $dropdown.hasClass('open');

                    // Close all others
                    $('.custom-select-dropdown').removeClass('open');
                    $('.custom-select-trigger').removeClass('open');

                    if (!isOpen) {
                        $dropdown.addClass('open');
                        $trigger.addClass('open');
                        $search.val('').focus();
                        // Reset search filter
                        $wrapper.find('.custom-select-options li').removeClass('hidden');
                    }
                });

                // Select / deselect
                $wrapper.find('.custom-select-options').on('click', 'li', function(e) {
                    e.stopPropagation();
                    const $li = $(this);
                    const value = String($li.data('value'));

                    if ($li.hasClass('selected')) {
                        $li.removeClass('selected');
                        $wrapper.find('input[name="' + inputName + '[]"][value="' + value + '"]').remove();
                    } else {
                        $li.addClass('selected');
                        if ($wrapper.find('input[name="' + inputName + '[]"][value="' + value + '"]').length === 0) {
                            $wrapper.append('<input type="hidden" name="' + inputName + '[]" value="' + value + '">');
                        }
                    }

                    updateTriggerText($wrapper, $placeholder, placeholder);
                });

                // Search filter
                $search.on('keyup', function() {
                    const term = $(this).val().toLowerCase();
                    $wrapper.find('.custom-select-options li').each(function() {
                        const match = $(this).text().toLowerCase().includes(term);
                        $(this).toggleClass('hidden', !match);
                    });
                });

                // Prevent dropdown close when clicking inside it
                $dropdown.on('click', function(e) {
                    e.stopPropagation();
                });
            }

            function updateTriggerText($wrapper, $placeholder, placeholder) {
                const count = $wrapper.find('.custom-select-options li.selected').length;
                const label = $placeholder.data('label') || 'item';

                if (count === 0) {
                    $placeholder.text(placeholder).removeClass('has-selection');
                } else {
                    $placeholder.text(count + ' ' + label + (count > 1 ? 's' : '') + ' selected')
                        .addClass('has-selection');
                }
            }

            // Close on outside click
            $(document).on('click', function() {
                $('.custom-select-dropdown').removeClass('open');
                $('.custom-select-trigger').removeClass('open');
            });

            // Init all custom selects
            $(document).ready(function() {
                initCustomSelect('department_select', 'department_ids', 'Select Departments');
                initCustomSelect('job_role_select', 'job_role_ids', 'Select Job Roles');
                initCustomSelect('branch_select', 'branch_ids', 'Select Branches');
                initCustomSelect('course_select', 'course_ids', 'Select Courses');
            });

        $('#company_id').on('change', function() {
            let companyId = $(this).val();
            const $wrapper = $('#branch_select');
            const $options = $wrapper.find('.custom-select-options');

            $options.empty();
            $wrapper.find('input[name="branch_ids[]"]').remove();
            $wrapper.find('.select-placeholder').text('Select Branches').removeClass('has-selection');

            if (!companyId) return;

            $.ajax({
                url: "{{ route('twill.getBranchesForCompany') }}",
                type: 'GET',
                data: {
                    'company_id[]': [companyId]  // send as array
                },
                success: function(response) {
                    const branches = response.status === 'success' ? response.data : [];

                    if (!branches.length) {
                        $options.append(`
                            <li class="disabled" style="color:#999; cursor:not-allowed;">
                                No branches available
                            </li>
                        `);
                        return;
                    }

                    branches.forEach(function(branch) {
                        $options.append(`
                            <li data-value="${branch.value}">
                                ${branch.label}
                            </li>
                        `);
                    });
                },
                error: function() {
                    console.error('Failed to load branches');
                }
            });
        });

        function loadJobRoles(departmentIds) {
            const $wrapper = $('#job_role_select');
            const $options = $wrapper.find('.custom-select-options');

            if (!departmentIds || departmentIds.length === 0) {
                return;
            }

            $options.empty();
            $wrapper.find('input[name="job_role_ids[]"]').remove();
            $wrapper.find('.select-placeholder')
                .text('Select Job Roles')
                .removeClass('has-selection');

            $.ajax({
                url: "{{ route('twill.getJobRolesForDepartment') }}",
                type: "GET",
                data: {
                    'department_ids[]': departmentIds  // send as array
                },
                success: function(response) {
                    const jobroles = response.status === 'success' ? response.data : [];

                    if (!jobroles.length) {
                        $options.append(`
                            <li style="color:#999;">
                                No job roles available
                            </li>
                        `);
                        return;
                    }

                    jobroles.forEach(function(jobrole) {
                        $options.append(`
                            <li data-value="${jobrole.value}">
                                ${jobrole.label}
                            </li>
                        `);
                    });
                }
            });
        }

            const observer = new MutationObserver(function() {

                let departmentIds = [];

                $('#department_select input[name="department_ids[]"]').each(function() {
                    departmentIds.push($(this).val());
                });

                loadJobRoles(departmentIds);

            });

            observer.observe(document.getElementById('department_select'), {
                childList: true,
                subtree: true
            });
        </script>
    @endpush
