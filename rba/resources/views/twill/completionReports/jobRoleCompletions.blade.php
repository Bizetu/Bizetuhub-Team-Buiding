@extends('twill::layouts.free')
@push('extra_css')
	<link href="{{ asset('css/twill/reports.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
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
        .container {
            width: 100% !important;
        }
		.report_visual {
			margin-bottom: 30px;
		}
        .dropdown,
        .dropup {
            position: relative;
        }
        .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
            width: 75%;
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
        .a17 .vselect__field .dropdown-toggle {
            height:43px !important;
        }
        .date_range_picker {
            height:43px !important;
        }
        .filter-select {
            height:43px !important;
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
        .filter-bar {
            padding: 0 5px;
            margin-bottom: 10px;
        }
        .filter-bar .filter-label {
            font-weight: 600;
            font-size: 13px;
            color: #444;
            margin-bottom: 6px;
            display: block;
            white-space: nowrap;
            height: 20px;
            line-height: 20px;
        }
        .filter-bar .form-control {
            height: 40px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            font-size: 14px;
        }
        .filter-bar .btn-action {
            height: 40px;
            padding: 0 18px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .filter-bar .btn-search  { background: #1A8F36; color: #fff; }
        .filter-bar .btn-search:hover  { background: #157a2d; }
        .filter-bar .btn-reset   { background: #FF0000; color: #fff; }
        .filter-bar .btn-reset:hover   { background: #ff2238; color: #fff; }
        .filter-bar .btn-export  { background: #1A8F36; color: #fff; }
        .filter-bar .btn-export:hover  { background: #157a2d; }
        .filter-bar .actions {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 40px;
        }
        .bootstrap-select .dropdown-menu.inner li.selected > a {
            background-color: #e6f0ff !important;
        }
	</style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('customPageContent')
<div class="listing">

    <div id="container-fluid">
        <div class="filter-bar">
            <div class="row">

                {{-- Filter by job role --}}
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <label class="filter-label">Filter by Job Role</label>

                    <select 
                        name="role_id[]" 
                        id="role_id" 
                        class="filter-select selectpicker" 
                        multiple
                        title="Select job role"
                        data-selected-text-format="count > 0"
                        data-count-selected-text="{0} Job Roles Selected"
                    >
                        @foreach (App\Models\JobRole::published()->get() as $theme)
                            <option value="{{ $theme->id }}"
                                {{ !empty($activeRoleId) && in_array($theme->id, $activeRoleId) ? 'selected' : '' }}>
                                {{ $theme->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search input --}}
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <label class="filter-label">Search</label>
                    <input
                        type="text"
                        placeholder="Search by job role..."
                        class="form-control"
                        id="searchInput"
                        value="{{ $activeSearchTerm ?? '' }}"
                    >
                </div>

                <div class="col-12 col-md-4 mb-0">
                    <label class="filter-label">&nbsp;</label>
                    <div class="actions">

                        <form action="{{ route('twill.jobRoleCompletions') }}" method="POST" id="searchForm">
                            @csrf
                            <input type="hidden" name="search" id="searchFormSearch">
                            <button type="submit" class="btn-action btn-search">Search</button>
                        </form>

                        <a href="{{ route('twill.jobRoleCompletions') }}" class="btn-action btn-reset">Reset</a>

                        <form action="{{ route('twill.export.jobRoleCompletions') }}" method="POST" id="exportForm">
                            @csrf
                            <input type="hidden" name="search" id="exportFormSearch">
                            <button type="submit" class="btn-action btn-export">Export</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-bordered za-kuexport">
                    <thead>
                        <tr class="tablehead">
                            <th style="text-align: left;font-weight:bold">#</th>
                            <th style="text-align: left;font-weight:bold">Job Role</th>
                            <th style="text-align: center;width:20%;font-weight:bold;">Total Staff Registered</th>
                            <th style="text-align: center;width:30%;font-weight:bold;">Total Staff Who’ve Completed Mandatory Modules</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($reportData) > 0)
                            @foreach($reportData as $index => $data)
                                <tr class="tablerow">
                                    <td class="tablecell">{{ $index + 1 }}</td>
                                    <td class="tablecell">{{ $data['title'] }}</td>
                                    <td class="tablecell" style="text-align: center;">{{ $data['registered_users'] }}</td>
                                    <td class="tablecell" style="text-align: center;">{{ $data['user_completed'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">Records not found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@push('extra_js')
    <script src="{{ twillAsset('main-free.js') }}" crossorigin></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/xlsx.full.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#role_id').selectpicker();

            function syncFilters(formId) {
                var form = document.getElementById(formId);
                form.querySelectorAll('input[name="role_id[]"]').forEach(el => el.remove());
                $('#role_id').val().forEach(function (id) {
                    var input = document.createElement('input');
                    input.type  = 'hidden';
                    input.name  = 'role_id[]';
                    input.value = id;
                    form.appendChild(input);
                });
                form.querySelector('input[name="search"]').value = $('#searchInput').val();
            }
            $('#searchForm').on('submit', function () {
                syncFilters('searchForm');
            });
            $('#exportForm').on('submit', function () {
                syncFilters('exportForm');
            });
        });
    </script>
@endpush