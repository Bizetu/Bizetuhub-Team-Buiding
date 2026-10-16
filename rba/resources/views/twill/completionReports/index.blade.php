@php
    $route = request()->route()->getName();
    $currentRoute = route($route);
    $dateColumns = ['enrollment_date', 'completion_date'];
@endphp
@extends('twill::layouts.main')

@push('extra_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        @font-face {
            font-family: "Glyphicons Halflings";
            src: url(../../../../public/fonts/glyphicons-halflings-regular.eot);
            src: url(../../../../public/fonts/glyphicons-halflings-regular.eot?#iefix) format("embedded-opentype"), url(../../../../public/fonts/glyphicons-halflings-regular.woff2) format("woff2"), url(../../../../public/fonts/glyphicons-halflings-regular.woff) format("woff"),
                url(../../../../public/fonts/glyphicons-halflings-regular.ttf) format("truetype"), url(../../../../public/fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format("svg");
        }

        a {
            color: #ffffff;
        }

        .dropdown,
        .dropup {
            position: relative;
        }

        .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
            width: 330px;
        }

        .add-button {
            float: right;
            height: 35px;
            line-height: 33px;
            padding: 0 25px;
            background: #1d9f3c;
            border: 1px solid #1d9f3c;
            color: #fff;
            text-decoration: none;

            margin-left: 1%;
        }

        .remove-button {
            float: right;
            height: 35px;
            line-height: 33px;
            padding: 0 25px;
            background: red;
            border: 1px solid red;
            color: #fff;
            text-decoration: none;

            margin-left: 1%;
        }

        .cancel-button {
            float: right;
            height: 35px;
            line-height: 33px;
            padding: 0 25px;
            background: grey;
            border: 1px solid grey;
            color: #fff;
            text-decoration: none;

            margin-left: 1%;
        }

        .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            display: none;
            overflow: hidden;
            -webkit-overflow-scrolling: touch;
            outline: 0;
        }

        .modal-open .modal {
            overflow-x: hidden;
            overflow-y: auto;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 10px;
        }

        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #999;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: 6px;
            outline: 0;
            -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
            box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
            min-height: 195px;
        }

        .modal-body {
            position: relative;
            padding: 15px;
        }

        .modal-footer {
            padding: 15px;
            text-align: right;
            border-top: 1px solid #e5e5e5;
        }

        .modal-md {
            max-width: 500px;
            margin: 20% auto;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .btn-default {
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }

        .btn .caret {
            margin-left: 0;
        }

        .caret {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px dashed;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            text-align: left;
            list-style: none;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        }

        .open>.dropdown-menu {
            display: block;
        }

        .dropdown-menu>li>a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: 400;
            line-height: 1.42857143;
            color: #333;
            white-space: nowrap;
        }

        .glyphicon {
            position: relative;
            top: 1px;
            display: inline-block;
            font-family: 'Glyphicons Halflings';
            font-style: normal;
            font-weight: 400;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .glyphicon.glyphicon-ok {
            display: inline-block;
            transform: rotate(45deg);
            height: 14px;
            width: 7px;
            margin-left: 60%;
            border-bottom: 2px solid #78b13f;
            border-right: 2px solid #78b13f;
        }

        .filter__more {
            display: block !important;
            overflow: visible !important;
        }

        .filter__toggle.button.button--ghost {
            display: none !important;
        }

        .container {
            width: 100% !important;
        }

        .a17 .vselect__field .dropdown-toggle {
            height: 43px !important;
        }

        .date_range_picker {
            height: 32px !important;
            font-size: 14px;
        }

        .filter-select {
            height: 43px !important;
            margin-bottom: 2rem;
        }

        .filter .filter__moreInner>div {
            display: contents !important;
        }

        .filter .filter__moreInner>div>* {
            margin-right: 20px;
            width: 14%;
        }

        .filter .filter__moreInner>select {
            margin-right: 20px;
            width: 9%;
        }

        .filter .filter__moreInner>div>label {
            margin-right: 20px;
            width: unset;
        }

        .export {
            height: 35px;
            line-height: 33px;
            border-radius: 17.5px;
            background-color: transparent;
            border: 1px solid #d9d9d9;
            color: #8c8c8c;
            padding: 0 20px;
            float: right;
            margin-right: 20px;
            margin-top: 10px;
            width: auto;
            cursor: pointer;
            float: right;
        }

        .export .icon {
            width: 16px;
            height: 16px;
            fill: currentColor;
            display: inline-block;
            vertical-align: middle;
        }

        /* dropdown option layout */
        .s2-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        /* tick */
        .s2-tick {
            color: #1F3B67;
            font-weight: bold;
        }

        /* selected item background */
        .select2-results__option[aria-selected=true] {
            background-color: #fff !important;
            /* light blue */
            color: #1F3B67 !important;
        }

        /* hover ONLY for unselected */
        .select2-results__option--highlighted:not([aria-selected=true]) {
            background-color: #f5f5f5 !important;
            color: #000 !important;
        }

        /* remove chips completely */
        .select2-selection__choice {
            display: none !important;
        }

        /* clean input */
        .select2-selection__rendered {
            padding-left: 8px;
            color: #666;
        }


        /* label styling (e.g. "3 selected") */
        .s2-label {
            font-size: 13px;
            color: #555;
        }

        /* spacing fix */
        .select2-container--default .select2-selection--multiple {
            margin-bottom: 1em !important;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: #e6f0ff !important;
            ;
        }

        .wide-select2 {
            width: 400px !important;
        }

        .glyphicon.glyphicon-ok {
            display: inline-block;
            transform: rotate(45deg);
            height: 14px;
            width: 7px;
            margin-left: 0%;
            border-bottom: 2px solid #78b13f;
            border-right: 2px solid #78b13f;
        }
    </style>
@endpush
@section('appTypeClass', 'body--listing')

@php
    $translate = $translate ?? false;
    $translateTitle = $translateTitle ?? ($translate ?? false);
    $reorder = $reorder ?? false;
    $nested = $nested ?? false;
    $bulkEdit = $bulkEdit ?? true;
    $create = $create ?? false;
    $skipCreateModal = $skipCreateModal ?? false;

    $requestFilter = json_decode(request()->get('filter'), true) ?? [];

@endphp

@push('extra_css')
    @if (app()->isProduction())
        <link href="{{ twillAsset('main-listing.css') }}" rel="preload" as="style" crossorigin />
    @endif
    @unless (config('twill.dev_mode', false))
        <link href="{{ twillAsset('main-listing.css') }}" rel="stylesheet" crossorigin />
    @endunless
@endpush

@push('extra_js_head')
    @if (app()->isProduction())
        <link href="{{ twillAsset('main-listing.js') }}" rel="preload" as="script" crossorigin />
    @endif
@endpush

@section('content')
    <div class="listing">
        <div class="listing__nav">
            <div class="container" ref="form">
                <a17-filter v-on:submit="filterListing" v-bind:closed="hasBulkIds"
                    initial-search-value="{{ $filters['search'] ?? '' }}" :clear-option="true"
                    v-on:clear="clearFiltersAndReloadDatas">
                    <a17-table-filters slot="navigation"></a17-table-filters>

                    @forelse($hiddenFilters as $filter)

                        @if ($loop->first)
                            <div slot="hidden-filters">
                        @endif

                        @php
                            $filterKey = is_string($filter) ? $filter : str_replace('List', '', $filter->getKey());
                            $filterList = $filterKey . 'List';
                            $filterLabels = [
                                'company_id' => 'Company',
                                'message_type' => 'Message Category',
                                'job_role_id' => 'Job Role',
                                'branch_id' => 'Branch',
                                'department_id' => 'Department',
                                'course_id' => 'Course',
                                'completion_status' => 'Completion Status',
                                'enrollment_date' => 'Enrollment Date',
                                'completion_date' => 'Completion Date',
                            ];

                            $label = $filterLabels[$filterKey] ?? $filter->label;

                        @endphp

                        @if (isset(${$filterList}) && !in_array($filterKey, $dateColumns))
                            @php
                                $list = ${$filterList};

                                $options = collect($list)
                                    ->reject(fn($item, $key) => $key === 'all') // remove "all" if exists
                                    ->map(function ($item, $key) {
                                        // Case 1: object (company, job role, etc.)
                                        if (is_object($item)) {
                                            return [
                                                'value' => $item->id,
                                                'label' => $item->title,
                                            ];
                                        }

                                        // Case 2: key-value (Message Category)
                                        return [
                                            'value' => $key,
                                            'label' => $item,
                                        ];
                                    })
                                    ->values()
                                    ->toArray();

                                $selectedIndex = isset($requestFilter[$filterKey])
                                    ? array_search($requestFilter[$filterKey], array_column($options, 'value'))
                                    : false;
                            @endphp

                            @php
                                $selected = $requestFilter[$filterKey] ?? [];
                                $selected = is_array($selected) ? $selected : explode(',', $selected);
                                // Dependency map
                                $dependsOnMap = [
                                    'branch_id' => [
                                        'depends_on' => 'company_id',
                                        'fetch_url' => route('twill.getBranchesForCompany'),
                                        'url_style' => 'query',
                                    ],
                                    'job_role_id' => [
                                        'depends_on' => 'department_id',
                                        'fetch_url' => route('twill.getJobRolesForDepartment'),
                                        'url_style' => 'query',
                                    ],
                                ];
                                $depConfig = $dependsOnMap[$filterKey] ?? null;
                            @endphp
                            <select name="{{ $filterKey }}" class="form-control js-select2-tick" multiple
                                data-placeholder="Select {{ ucfirst(str_replace('_', ' ', $label)) }}"
                                data-filter="{{ $filterKey }}" data-label="{{ $label }}"
                                @if ($depConfig) data-depends-on="{{ $depConfig['depends_on'] }}"
                data-fetch-url="{{ $depConfig['fetch_url'] }}"
                data-url-style="{{ $depConfig['url_style'] }}" @endif
                                style="width: 350px">
                                @foreach ($options as $option)
                                    <option value="{{ $option['value'] }}"
                                        {{ in_array($option['value'], (array) ($requestFilter[$filterKey] ?? [])) ? 'selected' : '' }}>
                                        {{ $option['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            @if ($filterKey === 'send_dates')
                                <label>{{ 'Send Date' }}</label>
                            @elseif($filterKey === 'enrollment_dates')
                                <label>{{ 'Enrollment Date' }}</label>
                            @endif
                            <input type="text" name="{{ $filterKey }}" class="date_range_picker"
                                placeholder="Select {{ $label }} Range" style="width:250px" />
                        @endif

                        @if ($loop->last)
            </div>
            @endif
        @empty
            @hasSection('hiddenFilters')
                <div slot="hidden-filters">
                    @yield('hiddenFilters')
                </div>
            @endif
            @endforelse

            @if ($create)
                <div slot="additional-actions">
                    <a17-button variant="validate" size="small"
                        @if ($skipCreateModal) href={{ $createUrl ?? '' }} el="a" @endif
                        @if (!$skipCreateModal) v-on:click="create" @endif>
                        {{ twillTrans('twill::lang.listing.add-new-button') }}
                    </a17-button>
                    @foreach ($filterLinks as $link)
                        <a17-button el="a" href="{{ $link['url'] ?? '#' }}"
                            download="{{ $link['download'] ?? '' }}" rel="{{ $link['rel'] ?? '' }}"
                            target="{{ $link['target'] ?? '' }}" variant="small secondary">{{ $link['label'] }}
                        </a17-button>
                    @endforeach
                </div>
            @elseif(isset($filterLinks) && count($filterLinks))
                <div slot="additional-actions">
                    @foreach ($filterLinks as $link)
                        <a17-button el="a" href="{{ $link['url'] ?? '#' }}"
                            download="{{ $link['download'] ?? '' }}" rel="{{ $link['rel'] ?? '' }}"
                            target="{{ $link['target'] ?? '' }}" variant="small secondary">{{ $link['label'] }}
                        </a17-button>
                    @endforeach
                </div>
            @endif

            </a17-filter>

        </div>
        @if ($bulkEdit)
            <a17-bulk></a17-bulk>
        @endif

        <div class="container" style="margin-bottom: 3%;">
            <div class="row">
                <div class="col-12">
                    <button id="exportButton" class="button export"><svg xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 384 512" class="icon">
                            <path
                                d="M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zm60.1 106.5L224 336l60.1 93.5c5.1 8-.6 18.5-10.1 18.5h-34.9c-4.4 0-8.5-2.4-10.6-6.3C208.9 405.5 192 373 192 373c-6.4 14.8-10 20-36.6 68.8-2.1 3.9-6.1 6.3-10.5 6.3H110c-9.5 0-15.2-10.5-10.1-18.5l60.3-93.5-60.3-93.5c-5.2-8 .6-18.5 10.1-18.5h34.8c4.4 0 8.5 2.4 10.6 6.3 26.1 48.8 20 33.6 36.6 68.5 0 0 6.1-11.7 36.6-68.5 2.1-3.9 6.2-6.3 10.6-6.3H274c9.5-.1 15.2 10.4 10.1 18.4zM384 121.9v6.1H256V0h6.1c6.4 0 12.5 2.5 17 7l97.9 98c4.5 4.5 7 10.6 7 16.9z">
                            </path>
                        </svg> <span id="exportText">&nbsp;Export</span> <span id="spinner" role="status"
                            aria-hidden="true" class="spinner-border spinner-border-sm d-none"></span></button>

                </div>
            </div>
        </div>

    </div>
    @if ($nested)
        <a17-nested-datatable :draggable="{{ $reorder ? 'true' : 'false' }}" :max-depth="{{ $nestedDepth ?? '1' }}"
            :bulkeditable="{{ $bulkEdit ? 'true' : 'false' }}"
            empty-message="{{ twillTrans('twill::lang.listing.listing-empty-message') }}">
            <template
                #default="{ bulkActions, bulkEdit, bulkChecked, bulkCheckAll, bulkEditVisible, listing, deleteItems, restoreItems }">
                //content goes here
            </template>
        </a17-nested-datatable>
    @else
        <a17-datatable class="za-kuexport" :draggable="{{ $reorder ? 'true' : 'false' }}"
            :bulkeditable="{{ $bulkEdit ? 'true' : 'false' }}"
            empty-message="{{ twillTrans('twill::lang.listing.listing-empty-message') }}">
            <template
                #default="{ bulkActions, bulkEdit, bulkChecked, bulkCheckAll, bulkEditVisible, listing, deleteItems, restoreItems }">
                //content goes here
            </template>
        </a17-datatable>
    @endif

    @if ($create)
        <a17-modal-create ref="editionModal" form-create="{{ $storeUrl }}" v-on:reload="reloadDatas"
            @if ($customPublishedLabel ?? false) published-label="{{ $customPublishedLabel }}" @endif
            @if ($customDraftLabel ?? false) draft-label="{{ $customDraftLabel }}" @endif>
            <a17-langmanager></a17-langmanager>
            @partialView($moduleName ?? null, 'create', ['renderForModal' => true])
        </a17-modal-create>
    @endif

    <a17-dialog ref="warningDeleteRow" modal-title="{{ twillTrans('twill::lang.listing.dialogs.delete.title') }}"
        confirm-label="{{ twillTrans('twill::lang.listing.dialogs.delete.confirm') }}">
        <p class="modal--tiny-title"><strong>{{ twillTrans('twill::lang.listing.dialogs.delete.move-to-trash') }}</strong>
        </p>
        <p>{{ twillTrans('twill::lang.listing.dialogs.delete.disclaimer') }}</p>
    </a17-dialog>

    <a17-dialog ref="warningDestroyRow" modal-title="{{ twillTrans('twill::lang.listing.dialogs.destroy.title') }}"
        confirm-label="{{ twillTrans('twill::lang.listing.dialogs.destroy.confirm') }}">
        <p class="modal--tiny-title">
            <strong>{{ twillTrans('twill::lang.listing.dialogs.destroy.destroy-permanently') }}</strong>
        </p>
        <p>{{ twillTrans('twill::lang.listing.dialogs.destroy.disclaimer') }}</p>
    </a17-dialog>
    </div>

@stop

@section('initialStore')

    window['{{ config('twill.js_namespace') }}'].CMS_URLS = {
    index: @if (isset($indexUrl))
        '{{ $indexUrl }}'
    @else
        window.location.href.split('?')[0]
    @endif,
    publish: '{{ $publishUrl }}',
    bulkPublish: '{{ $bulkPublishUrl }}',
    restore: '{{ $restoreUrl }}',
    bulkRestore: '{{ $bulkRestoreUrl }}',
    forceDelete: '{{ $forceDeleteUrl }}',
    bulkForceDelete: '{{ $bulkForceDeleteUrl }}',
    reorder: '{{ $reorderUrl }}',
    create: '{{ $createUrl ?? '' }}',
    feature: '{{ $featureUrl }}',
    bulkFeature: '{{ $bulkFeatureUrl }}',
    bulkDelete: '{{ $bulkDeleteUrl }}'
    }

    window['{{ config('twill.js_namespace') }}'].STORE.form = {
    fields: []
    }

    window['{{ config('twill.js_namespace') }}'].STORE.datatable = {
    data: {!! json_encode($tableData) !!},
    columns: {!! json_encode($tableColumns) !!},
    navigation: {!! json_encode($tableMainFilters) !!},
    filter: { status: '{{ $filters['status'] ?? ($defaultFilterSlug ?? 'all') }}' },
    page: '{{ request('page') ?? 1 }}',
    maxPage: '{{ $maxPage ?? 1 }}',
    defaultMaxPage: '{{ $defaultMaxPage ?? 1 }}',
    offset: '{{ request('offset') ?? ($offset ?? 60) }}',
    defaultOffset: '{{ $defaultOffset ?? 60 }}',
    sortKey: '{{ $reorder ? request('sortKey') ?? '' : request('sortKey') ?? '' }}',
    sortDir: '{{ request('sortDir') ?? 'asc' }}',
    baseUrl: '{{ rtrim(config('app.url'), '/') . '/' }}',
    localStorageKey:
    '{{ isset($currentUser) ? $currentUser->id : 0 }}__{{ $moduleName ?? Route::currentRouteName() }}'
    }

    @if ($create && ($openCreate ?? false))
        window['{{ config('twill.js_namespace') }}'].openCreate = {!! json_encode($openCreate) !!}
    @endif
@stop

@push('extra_js')
    <script src="{{ twillAsset('main-listing.js') }}" crossorigin></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            // ── Core helpers ───────────────────────────────────────────────────────

            function initSelect2($el) {
                $el.on('select2:open', function () {
                    $('.select2-container--open').addClass('wide-select2');
                });

                $el.on('select2:close', function () {
                    $('.select2-container').removeClass('wide-select2');
                });
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.select2('destroy');
                }

                $el.select2({
                    width: '250px',
                    closeOnSelect: false,
                    minimumResultsForSearch: Infinity,

                    templateResult: function(data) {
                        if (!data.id) return data.text;
                        const isSelected = $(data.element).is(':selected');
                        return $(`
                    <div class="s2-option">
                        <span>${data.text}</span>
                        ${isSelected ? '<span class="glyphicon glyphicon-ok s2-tick"></span>' : ''}
                    </div>
                `);
                    },

                    templateSelection: function() {
                        return '';
                    }
                });

                // Rebind events after (re)init
                $el.off('select2:select select2:unselect').on('select2:select select2:unselect', function() {
                    const $this = $(this);
                    $this.select2('close');
                    $this.select2('open');
                    updateLabel($this);
                    triggerDependents($this);
                });

                updateLabel($el);
            }

            function updateLabel($select) {
                const count = $select.val() ? $select.val().length : 0;
                const label = $select.data('label') || 'item';

                const pluralMap = {
                    branch: 'Branches',
                    company: 'Companies',
                    department: 'Departments',
                    'job role': 'Job joles',
                    'completion status': 'Completion statuses'
                };

                const plural = pluralMap[label.toLowerCase()] || `${label}s`;

                const $rendered = $select
                    .next('.select2-container')
                    .find('.select2-selection__rendered');

                if (count > 0) {
                    const text = count === 1 ?
                        `${count} ${label} selected` :
                        `${count} ${plural} selected`;
                    $rendered.html(`<span class="s2-label" style="padding-left: 1%;">${text}</span>`);
                } else {
                    $rendered.html('');
                }
            }

            // ── Dependent loading ──────────────────────────────────────────────────

            function triggerDependents($parent) {
                const parentFilter = $parent.data('filter');
                const parentValues = $parent.val() || [];

                $('[data-depends-on="' + parentFilter + '"]').each(function() {
                    const $child = $(this);
                    const fetchUrl = $child.data('fetch-url');
                    const urlStyle = $child.data('url-style') || 'query';
                    // Clear options and show loading — do NOT disable the <select>
                    $child.empty();
                    $child.append('<option value="">Loading…</option>');

                    // Destroy and reinit cleanly (element is NOT disabled)
                    if ($child.hasClass('select2-hidden-accessible')) {
                        $child.select2('destroy');
                    }

                    // Visually grey it out via the container after init
                    $child.select2({
                        width: '250px',
                        closeOnSelect: false,
                        minimumResultsForSearch: Infinity,
                        templateResult: function(data) {
                            if (!data.id) return data.text;
                            const isSelected = $(data.element).is(':selected');
                            return $(`
                    <div class="s2-option">
                        <span>${data.text}</span>
                        ${isSelected ? '<span class="s2-tick">✔</span>' : ''}
                    </div>
                `);
                        },
                        templateSelection: function() {
                            return '';
                        }
                    });
                    $child.next('.select2-container').css('opacity', '0.5');
                    updateLabel($child);
                    console.log(parentValues)

                    // if (!parentValues.length || !fetchUrl) {
                    //     return;
                    // }

                    // Build URL
                    let url;
                    if (urlStyle === 'path') {
                        url = fetchUrl + '/' + parentValues[0];
                    } else {
                        const params = new URLSearchParams();
                        parentValues.forEach(v => params.append(parentFilter + '[]', v));
                        url = fetchUrl + '?' + params.toString();
                    }

                    $.getJSON(url)
                        .done(function(response) {

                            $child.empty();

                            if (response.status === 'success' && response.data && response.data
                                .length) {
                                response.data.forEach(function(item) {
                                    $child.append(
                                        $('<option>', {
                                            value: item.value,
                                            text: item.label
                                        })
                                    );
                                });
                            } else {
                                $child.append('<option value="">No options available</option>');
                            }

                            // Destroy, reinit, restore opacity — element was never disabled
                            if ($child.hasClass('select2-hidden-accessible')) {
                                $child.select2('destroy');
                            }
                            initSelect2($child);
                            $child.next('.select2-container').css('opacity', '1');
                        })
                        .fail(function() {
                            $child.empty();
                            $child.append('<option value="">Failed to load</option>');
                            if ($child.hasClass('select2-hidden-accessible')) {
                                $child.select2('destroy');
                            }
                            initSelect2($child);
                            $child.next('.select2-container').css('opacity', '0.5');
                        });
                });
            }

            // ── Boot ───────────────────────────────────────────────────────────────

            $('.js-select2-tick').each(function() {
                const $el = $(this);
                const dependsOn = '';

                initSelect2($el); // init first so container exists

                if (dependsOn) {
                    const $parent = $('[data-filter="' + dependsOn + '"]');
                    const parentHasValue = $parent.val() && $parent.val().length > 0;
                    if (!parentHasValue) {
                        // Grey out visually but don't disable the <select>
                        $el.next('.select2-container').css('opacity', '0.5');
                        // Prevent opening when greyed out
                        $el.on('select2:opening', function(e) {
                            if ($(this).next('.select2-container').css('opacity') === '0.5') {
                                e.preventDefault();
                            }
                        });
                    }
                }
            });

        });
    </script>
    <script type="text/javascript">
        $(function() {

            $('.date_range_picker').each(function() {
                const $input = $(this);

                const hasCustom = $input.data('custom-range');

                $input.daterangepicker({
                    autoUpdateInput: false,
                    opens: "center",
                    locale: {
                        cancelLabel: 'Clear',
                        ...(hasCustom ? {
                            customRangeLabel: "Custom"
                        } : {})
                    }
                });

                $input.on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(
                        picker.startDate.format('MM/DD/YYYY') +
                        ' - ' +
                        picker.endDate.format('MM/DD/YYYY')
                    );
                });

                $input.on('cancel.daterangepicker', function() {
                    $(this).val('');
                });
            });

        });
    </script>



    <script>
        // display a modal (small modal)
        $(document).on('click', '#smallButton', function(event) {
            event.preventDefault();

            $('#smallModal').modal("show");
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/xlsx.full.min.js"></script>

    <script>
        document.getElementById('exportButton').addEventListener('click', function() {
            const button = this;
            const exportText = document.getElementById('exportText');
            const spinner = document.getElementById('spinner');

            // Update UI to show exporting
            button.disabled = false;
            exportText.textContent = 'Exporting...';
            spinner.classList.remove('d-none');

            // Construct export URL
            const currentUrl = new URL(window.location.href);
            const queryParams = currentUrl.search;
            const exportRoute = "{{ route('twill.export.completionReports') }}";
            const exportUrl = exportRoute + queryParams;

            // Redirect to start download
            window.location.href = exportUrl;

            // Restore UI after delay (approximate)
            setTimeout(() => {
                button.disabled = false;
                exportText.textContent = 'Export';
                spinner.classList.add('d-none');
            }, 5000); // Delay assumes download starts within 5s, adjust if needed
        });

        //Reset to the current route when clear is clicked
        window.ParentUrl = "{{ $currentRoute }}";

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('button.button--ghost');

            if (btn && btn.textContent.trim() === 'Clear') {
                window.location.href = window.ParentUrl;
            }
        });
    </script>
@endpush
