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

        .container {
            width: 100% !important;
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
		.report_visual {
			margin-bottom: 30px;
		}
        .container {
            width: 100% !important;
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
        <div class="row">
            <div class="col-12">
                <form action="{{ route('twill.courseAverage') }}" method="POST" >
                     @csrf
                    <div class="row justify-content-center">
                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="col-12 no-padding-right ">
                            <div class="row">
                                <div class="col-4 d-flex">
                                    <label class="filter_by mt-4 mb-4 " style="color:#000; float:right"> Filter by course</label>
                                        <select 
                                            name="course_id[]" 
                                            id="course_id" 
                                            class="filter-select mt-4 mb-4 ml-2 selectpicker" 
                                            multiple
                                            data-selected-text-format="count > 0"
                                            title="Select Course"
                                            data-count-selected-text="{0} Courses Selected"
                                        >
                                        <option value=''>Select course</option>
                                        @foreach ($allCourses as $course)
                                            @if(!empty($activeCourseId))
                                                @if(in_array( $course->id, $activeCourseId))
                                                    <option value="{{ $course->id }}" selected> {{ $course->display_name }} </option>
                                                @else
                                                    <option value="{{ $course->id }}"> {{ $course->display_name }} </option>
                                                @endif
                                            @else 
                                                <option value="{{ $course->id }}"> {{ $course->display_name }} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="text" placeholder="Search course" class="form-control mt-4 mb-4 " name="search" style="height: 34px;">
                                </div>
                                <div class="col-3">
                                    <button
                                        type="submit"
                                        class="mt-4 mb-4"
                                        style="margin-top: 40px; margin-left: 20px; color: rgb(255, 255, 255); background: #1a8f36; border:1px solid #1a8f36; padding: 8px; text-decoration: none;"
                                    >Search</button>

                                    <a href="{{route('twill.courseAverage')}}"
                                        type="button" 
                                        class=" mt-4 mb-4" 
                                        name="reset" 
                                        style="margin-top: 40px; margin-left: 20px; color: rgb(255, 255, 255); background: rgb(255, 0, 0); border:1px solid rgb(255, 0, 0); padding: 8px; text-decoration: none;" 
                                        
                                    >Reset</a>

                                    <button
                                        id="exportButton"
                                        type="button"
                                        class="mt-4 mb-4"
                                        style="color: rgb(255, 255, 255); background: #1a8f36; border:1px solid #1a8f36; padding: 8px; text-decoration: none; margin-top: 40px; margin-left: 20px"
                                    >Export</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-bordered za-kuexport">
                    <thead>
                        <tr>
                            <th style="text-align: start;font-weight:bold">#</th>
                            <th style="text-align: start;font-weight:bold">Module Name</th>
                            {{-- <th>Total Staff Enrolled</th> --}}
                            <th style="font-weight:bold">Total Completed</th>
                            <th style="font-weight:bold">Average Score (%)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reportData as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data['title'] }}</td>
                                {{-- <td>{{ $data['total_enrolled'] }}</td> --}}
                                <td style="text-align: center;">{{ $data['total_completed'] }}</td>
                                <td style="text-align: center;">{{ $data['average_score'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No records found
                                </td>
                            </tr>
                        @endforelse
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#course_id').selectpicker();
 
        // Export button click handler
        $('#exportButton').on('click', function() {
            // Get current filter values
            var courseIds = $('#course_id').val();
            var search = $('input[name="search"]').val();
 
            // Build export URL with query parameters
            var exportUrl = "{{ route('twill.export.courseAverage') }}";
            var params = [];
 
            if (courseIds && courseIds.length > 0) {
                courseIds.forEach(function(id) {
                    params.push('course_id[]=' + encodeURIComponent(id));
                });
            }
 
            if (search) {
                params.push('search=' + encodeURIComponent(search));
            }
 
            if (params.length > 0) {
                exportUrl += '?' + params.join('&');
            }
 
            // Redirect to export URL
            window.location.href = exportUrl;
        });
    });
</script>

@endpush