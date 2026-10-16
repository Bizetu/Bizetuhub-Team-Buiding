@php
    use App\Models\CompletionReport;
    use App\Models\JobRole;
    use App\Models\Course;
    use App\Models\User;

    $mandatoryCourses = Course::published()->pluck('id')->unique()->toArray();
    $completionReport = CompletionReport::where('user_id', $item->id)->orderBy('completion_date', 'desc')->get()->unique('course_id')->values();
    $mandatoryCompletions = $completionReport->whereIn('course_id', $mandatoryCourses)->values();
    $otherModules = $completionReport->whereNotIn('course_id', $mandatoryCourses)->values();

    $total = $mandatoryCompletions->count();
    $done  = count($mandatoryCourses);
    $pct   = round($item->average_score_overall * 100, 2);
    $authId = Auth::id();
    $index = 1;
    $index_mandatory = 1;
@endphp

@extends('twill::layouts.form', [
    'contentFieldsetLabel' => 'User Details',
    'editModalTitle' => twillTrans('twill::lang.user-management.edit-modal-title'),
    'reloadOnSuccess' => true
])

@php
    $isSuperAdmin = isset($item->role_id) ? $item->role_id === 1 : false;
@endphp

@section('contentFields')
    @can('edit-users')
        @formColumns
            @slot('left')
                <x-twill::input
                    name="name"
                    :label="twillTrans('twill::lang.user-management.name')"
                />
            @endslot
            @slot('right')
                @can('edit-user-roles')
                    <x-twill::input
                        name="email"
                        :label="twillTrans('twill::lang.user-management.email')"
                        :disabled="false"
                    />
                @else
                    <x-twill::input
                        name="email"
                        :label="twillTrans('twill::lang.user-management.email')"
                        :disabled="true"
                    />
                @endcan
            @endslot
        @endformColumns

        @formColumns
            @slot('left')
                @formField('input', [
                    'name'     => 'first_name',
                    'label'    => 'First Name',
                    'required' => true,
                ])
            @endslot
            @slot('right')
                @formField('input', [
                    'name'     => 'last_name',
                    'label'    => 'Last Name',
                    'required' => true,
                ])
            @endslot
        @endformColumns

        @if($item->id !== $currentUser->id)
            @formColumns
                @slot('left')
                    <x-twill::select
                        :name="'company_id'"
                        :label="'Company'"
                        :options="$companyOptions ?? []"
                        :placeholder="'Company'"
                        :disabled="$isHr ? true : false"
                    />
                @endslot
                @slot('right')
                    <x-twill::select
                        :name="'branch_id'"
                        :label="'Branch'"
                        :options="$branchOptions ?? []"
                        :placeholder="'Branch'"
                    />
                @endslot
            @endformColumns
            @formColumns
                @slot('left')
                    <x-twill::select
                        :name="'department_id'"
                        :label="'Department'"
                        :options="$departmentOptions ?? []"
                        :placeholder="'Department'"
                    />
                @endslot
                @slot('right')
                    <x-twill::select
                        :name="'job_role_id'"
                        :label="'Job Role'"
                        :options="$jobRoleOptions ?? []"
                        :placeholder="'Job Role'"
                    />
                @endslot
            @endformColumns
        @endif
    @endcan

    @if(config('twill.enabled.users-image'))
        <x-twill::medias
            name="profile"
            :label="twillTrans('twill::lang.user-management.profile-image')"
        />
    @endif

    @php
        $languageOptions = array_map(
            function($locale) {
                return [
                    'value' => $locale,
                    'label' => getLanguageLabelFromLocaleCode($locale, true)
                ];
            },
            config('twill.available_user_locales', ['en'])
        );
    @endphp

    @if(config('twill.enabled.users-description'))
        <x-twill::input
            name="title"
            :label="twillTrans('twill::lang.user-management.title')"
            :maxlength="250"
        />
        <x-twill::input
            name="description"
            :rows="4"
            type="textarea"
            :label="twillTrans('twill::lang.user-management.description')"
        />
        <x-twill::select
            name="language"
            :label="twillTrans('twill::lang.user-management.language')"
            :placeholder="twillTrans('twill::lang.user-management.language-placeholder')"
            :default="config('twill.locale', 'en')"
            :options="$languageOptions"
        />
    @endif

    @if($with2faSettings ?? false)
        <x-twill::checkbox
            name="google_2fa_enabled"
            :label="twillTrans('twill::lang.user-management.2fa')"
        />

        @unless($item->google_2fa_enabled ?? false)
            <x-twill::formConnectedFields
                field-name="google_2fa_enabled"
                :field-values="true"
            >
                <img style="display: block; margin-left: auto; margin-right: auto; max-height: 300px;"
                     src="{{ $qrCode }}">
                <div class="f--regular f--note"
                     style="margin: 20px 0;">{!! twillTrans('twill::lang.user-management.2fa-description', ['link' => 'https://github.com/antonioribeiro/google2fa#google-authenticator-apps']) !!}</div>
                <x-twill::input
                    name="verify-code"
                    :label="twillTrans('twill::lang.user-management.otp')"
                />
            </x-twill::formConnectedFields>
        @else
            <x-twill::formConnectedFields
                field-name="google_2fa_enabled"
                :field-values="false"
            >
                <x-twill::input
                    name="verify-code"
                    :label="twillTrans('twill::lang.user-management.otp')"
                    :note="twillTrans('twill::lang.user-management.2fa-disable')"
                />
            </x-twill::formConnectedFields>
        @endunless
    @endif

    @can('manage-users')
        @if(config('twill.enabled.users-2fa') && $item->google_2fa_enabled && ($item->id !== $currentUser->id))
            <x-twill::checkbox name="google_2fa_enabled" :label="twillTrans('twill::lang.user-management.2fa')"/>

            <x-twill::formConnectedFields
                field-name="google_2fa_enabled"
                :field-values="false"
            >
                <x-twill::input name="force-2fa-disable-challenge"
                                :label="twillTrans('twill::lang.user-management.force-2fa-disable')"
                                :note="twillTrans('twill::lang.user-management.force-2fa-disable-description')"
                                :placeholder="twillTrans('twill::lang.user-management.force-2fa-disable-challenge', ['user' => $item->email])"
                />
            </x-twill::formConnectedFields>
        @endif
    @endcan

    @if(\A17\Twill\Facades\TwillPermissions::levelIsOneOf([\A17\Twill\Enums\PermissionLevel::LEVEL_ROLE_GROUP, \A17\Twill\Enums\PermissionLevel::LEVEL_ROLE_GROUP_ITEM]))
        @can('edit-user-groups')
            <x-twill::browser
                module-name="groups"
                name="groups"
                label="Groups"
                :max="999"
            />
        @else
            @if($item->groups->count())
                @php
                    $groups = json_encode($item->groups->map(function ($group) {
                        return [
                            'label' => $group->name,
                            'value' => $group->id
                        ];
                    }));

                    $values = json_encode($item->groups->map(function ($group) {
                        return $group->id;
                    }));
                @endphp

                <a17-vselect
                    label="Groups"
                    name="groups_readonly"
                    :selected="{{ $groups }}"
                    :options="{{ $groups }}"
                    :multiple="true"
                    :disabled="true"
                ></a17-vselect>
            @endif
        @endcan
    @endif
@stop

@section('sideFieldsets')
    @can('edit-user-roles')
        <a17-fieldset title="Role" id="user_role_v">
            @if($item->id !== $currentUser->id)
                <x-twill::select
                    :name="$item->getRoleColumnName()"
                    :label="twillTrans('twill::lang.user-management.role')"
                    :options="$roleList ?? []"
                    :placeholder="twillTrans('twill::lang.user-management.role-placeholder')"
                />
            @endif
        </a17-fieldset>
    @else
        <a17-fieldset title="Role" id="user_role_v">
            <x-twill::select
                :name="$item->getRoleColumnName()"
                :label="twillTrans('twill::lang.user-management.role')"
                :options="$roleList ?? []"
                :placeholder="twillTrans('twill::lang.user-management.role-placeholder')"
                :disabled="true"
            />
        </a17-fieldset>
    @endcan
@endsection


@section('fieldsets')
    @if(\A17\Twill\Facades\TwillPermissions::levelIs(\A17\Twill\Enums\PermissionLevel::LEVEL_ROLE_GROUP_ITEM))
        @can('edit-users')
            @unless($item->isSuperAdmin() || $item->id === $currentUser->id)
                <x-twill::formConnectedFields
                    field-name="role_id"
                    :render-for-blocks="false"
                    :field-values="$item->role_id"
                >
                    @foreach($permissionModules as $permissionModuleName => $moduleItems)
                        <a17-fieldset title='{{ ucfirst($permissionModuleName) . " Permissions"}}' id='{{ $permissionModuleName }}'>
                            <x-twill::select-permissions
                                :items-in-selects-tables="$moduleItems"
                                label-key="title"
                                :name-pattern="$permissionModuleName . '_%id%_permission'"
                            />
                        </a17-fieldset>
                    @endforeach
                </x-twill::formConnectedFields>
            @endif
        @endcan
    @endif

    <hr>
    <div class="course-uptake-report" style="margin: 3rem 0;">
        <h3 style="color: #2c3e50; margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 600;">
            Course Uptake Report For {{$item->first_name }} {{$item->last_name }}
        </h3>

        <div style="margin-bottom: 2rem; padding: 1rem 1.25rem; background: #f0fdf4; border-left: 4px solid #1d9f3c;">
            <strong style="color: #1d9f3c;">Job Role:</strong>
            <span style="color: #374151;">{{$item->job_role_name_value }}</span>
            <br>
            <br>
            <strong style="color: #1d9f3c;">Total Training Hours:</strong>
            <span style="color: #374151;">{{$item->hours }}</span>
            <br>
            <br>
            <strong style="color: #1d9f3c;">Mandatory Modules Average:</strong>

            @if($item->mandatory_modules_average === '-')
                <span data-toggle="tooltip" data-placement="right" title="A user’s average score is generated only after they complete all their mandatory courses">
                    <svg style="width: 16px; height: 16px; color: #1d9f3c;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </span>
            @else
                <span style="color: #374151;">{{ $item->mandatory_modules_average }}</span>
            @endif
        </div>

        @if($completionReport->count() > 0)
            <div style="margin-bottom: 2.5rem;">
                <h4 style="color: #374151; margin-bottom: 1rem; font-size: 1.125rem; font-weight: 600;">Completed Modules</h4>
                <div style="border: 1px solid #e5e7eb; background: white; overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 600px;table-layout: fixed;">
                        <thead>
                            <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                <th style="width: 40%; padding: 0.875rem 1rem; text-align: left; font-weight: 600; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Course Name</th>
                                <th style="width: 20%; padding: 0.875rem 1rem; text-align: center; font-weight: 600; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                                <th style="width: 10%; padding: 0.875rem 1rem; text-align: center; font-weight: 600; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Required</th>
                                <th style="width: 10%; padding: 0.875rem 1rem; text-align: center; font-weight: 600; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Grade</th>
                                <th style="width: 20%; padding: 0.875rem 1rem; text-align: center; font-weight: 600; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Completion Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($mandatoryCompletions->count() > 0)
                                @foreach($mandatoryCompletions as $course)
                                    @php
                                        $status = $course->completion_status;
                                        $colors = [
                                            'Completed' => 'background: #1a8f36; color: #fff;',
                                        ];
                                        $statusStyle = $colors[$status] ?? 'background: #f3f4f6; color: #374151;';
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 0.5rem 1rem; color: #111827; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $index++ .'.  '. $course?->course_title_value ?? '-' }}</td>
                                        <td style="padding: 1rem;text-align:center;">
                                            <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.70rem; {{ $statusStyle }}">{{ $status }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span style="background:#d1fae5;color:#065f46;padding:4px 8px;border-radius:4px;">✓</span>
                                        </td>
                                        <td style="font-size: 0.75rem;text-align:center;">{{ $course->score_value . '%' }}</td>
                                        <td style="font-size: 0.75rem;text-align:center;">{{ $course->completion_date }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if($otherModules->count() > 0)
                                @foreach($otherModules as $course)
                                    @php
                                        $status = $course->completion_status;
                                        $colors = [
                                            'Completed' => 'background: #1a8f36; color: #fff;',
                                        ];
                                        $statusStyle = $colors[$status] ?? 'background: #f3f4f6; color: #374151;';
                                    @endphp

                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 0.5rem 1rem; color: #111827; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $index++ . '. ' . ($course->courses->name ?? 'N/A') }}
                                        </td>
                                        <td style="padding: 1rem;">
                                            <span style="padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.70rem; {{ $statusStyle }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:4px;">
                                                ✗
                                            </span>
                                        </td>
                                        <td style="font-size: 0.75rem;">{{ $course->score_average }}</td>
                                        <td style="font-size: 0.75rem;">{{ $course->completion_date }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 3rem 2rem; color: #9ca3af; background: #f9fafb; border: 1px dashed #e5e7eb; margin-top: 1rem;">
                <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p style="margin: 0; font-size: 0.875rem;">User has no completions</p>
            </div>
        @endif
    </div>

@stop

@push('vuexStore')
    window['{{ config('twill.js_namespace') }}'].STORE.publication.submitOptions = {
    draft: [
    {
    name: 'save',
    text: {!! json_encode(twillTrans('twill::lang.user-management.update-disabled-user')) !!}
    },
    {
    name: 'save-close',
    text: {!! json_encode(twillTrans('twill::lang.user-management.update-disabled-and-close')) !!}
    },
    {
    name: 'save-new',
    text: {!! json_encode(twillTrans('twill::lang.user-management.update-disabled-user-and-create-new')) !!}
    },
    {
    name: 'cancel',
    text: {!! json_encode(twillTrans('twill::lang.user-management.cancel')) !!}
    }
    ],
    live: [
    {
    name: 'publish',
    text: {!! json_encode(twillTrans('twill::lang.user-management.enable-user')) !!}
    },
    {
    name: 'publish-close',
    text: {!! json_encode(twillTrans('twill::lang.user-management.enable-user-and-close')) !!}
    },
    {
    name: 'publish-new',
    text: {!! json_encode(twillTrans('twill::lang.user-management.enable-user-and-create-new')) !!}
    },
    {
    name: 'cancel',
    text: {!! json_encode(twillTrans('twill::lang.user-management.cancel')) !!}
    }
    ],
    update: [
    {
    name: 'update',
    text: {!! json_encode(twillTrans('twill::lang.user-management.update')) !!}
    },
    {
    name: 'update-close',
    text: {!! json_encode(twillTrans('twill::lang.user-management.update-and-close')) !!}
    },
    {
    name: 'update-new',
    text: {!! json_encode(twillTrans('twill::lang.user-management.update-and-create-new')) !!}
    },
    {
    name: 'cancel',
    text: {!! json_encode(twillTrans('twill::lang.user-management.cancel')) !!}
    }
    ]
    }
    @unless($item->isSuperAdmin())
        @can('edit-users')
            window['{{ config('twill.js_namespace') }}'].STORE.publication.userInfo = {
            user_name: '{{ $item->name }}',
            registered_at: '{{ $item->isActivated() ? $item->registered_at->format('d M Y') : "Pending ({$item->created_at->format('d M Y')})" }}',
            last_login_at: '{{ $item->isActivated() && $item->last_login_at ? $item->last_login_at->format('d M Y, H:i') : null }}',
            resend_registration_link: '{{ $item->isPublished() && !$item->isActivated() ? route(config('twill.admin_route_name_prefix') . 'users.resend.registrationEmail', ['user' => $item]) : null }}',
            is_activated: {{ json_encode($item->isActivated()) }}
            }
        @endcan
    @endunless

    @if ($item->id == $currentUser->id)
        window['{{ config('twill.js_namespace') }}'].STORE.publication.withPublicationToggle = false
    @endif
@endpush

@push('extra_css')
    <style lang="scss" scoped>
        .f--link-underlined--o {
            display: none;
        }
    </style>
@endpush

@push('extra_js')
    <script>
        const formFields = {!! json_encode($form_fields) !!};
        const groupPermissionMapping = {!! json_encode($groupPermissionMapping ?? []) !!};
        var selectedGroups = formFields.browsers ? formFields.browsers.groups : []

        window['{{ config('twill.js_namespace') }}'].vm.$store.subscribe((mutation, state) => {
            const { type, payload } = mutation
            switch (type) {
                case 'saveSelectedItems':
                    selectedGroups = JSON.parse(JSON.stringify(payload))
                    selectedGroups.forEach((group) => {
                        const permissions = groupPermissionMapping[group['id']]
                        permissions.forEach((permission) => {
                            const fieldName = `${permission['permissionable_module']}_${permission['permissionable_id']}_permission`
                            const currentPermission = state['form']['fields'].find(function (e) {
                                return e.name === fieldName
                            })
                            // Only update when the permission is none.
                            if (!currentPermission || currentPermission.value === '') {
                                const field = {
                                    name: fieldName,
                                    value: 'view-item'
                                }
                                window['{{ config('twill.js_namespace') }}'].vm.$store.commit('updateFormField', field)
                            }
                        })
                    })
                    break

                case 'destroySelectedItem':
                    const group = selectedGroups[payload.index]
                    const permissions = groupPermissionMapping[group['id']]
                    permissions.forEach((permission) => {
                        const fieldName = `${permission['permissionable_module']}_${permission['permissionable_id']}_permission`
                        const currentPermission = state['form']['fields'].find(function (e) {
                            return e.name === fieldName
                        })
                        if (currentPermission && currentPermission.value === 'view-item') {
                            const field = {
                                name: fieldName,
                                value: ''
                            }
                            window['{{ config('twill.js_namespace') }}'].vm.$store.commit('updateFormField', field)
                        }
                    })
                    break
            }
        })
    </script>
@endpush
