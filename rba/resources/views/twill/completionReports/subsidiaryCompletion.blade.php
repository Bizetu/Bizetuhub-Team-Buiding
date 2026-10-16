@extends('twill::layouts.free')
@push('extra_css')
	<link href="{{ asset('css/twill/reports.css') }}" rel="stylesheet">
	<style>
		.report_visual {
			margin-bottom: 30px;
		}
        .container {
            width: 100% !important;
        }
        .export-btn {
            color: white;
            background: #1a8f36;
            border: 1px solid #1a8f36;
            padding: 10px;
            margin: 40px 0 0 20px;
            text-decoration: none;
            cursor: pointer;
        }
        .export-btn-red {
            margin-top: 40px;
            margin-left: 20px;
            color: rgb(255, 255, 255);
            background: rgb(255, 0, 0);
            border:1px solid rgb(255, 0, 0);
            padding: 10px;
            text-decoration: none;
            cursor: pointer;
        }
        .export-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .table td,
        .table th {
        text-align: start;
        }
	</style>
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('customPageContent')

    @php $notEmpty = $companies->total() > 0; @endphp

    <div class="listing">

        <div id="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('twill.subsidiaryCompletion') }}" method="POST" class="homeForm">
                        @csrf
                        <div class="row justify-content-center">

                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="col-9 no-padding-right ">
                                <div class="row">
                                    <div class="col-5">
                                        <input
                                            type="text"
                                            placeholder="Search Institution..."
                                            class="form-control mt-4 mb-4 "
                                            name="search"
                                            value="{{ $activeSearchTerm }}"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <button
                                    type="submit"
                                    class="mt-4 mb-4 export-btn"
                                >Search</button>

                                <button
                                    type="reset"
                                    name="reset"
                                    class="mt-4 mb-4 export-btn-red"
                                >Reset</button>

                                <button
                                    id="exportButton"
                                    class="mt-4 mb-4 export-btn"
                                    {{ !$notEmpty ? 'disabled' : '' }}
                                >Export </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: start;font-weight:bold">#</th>
                            <th style="text-align: start;font-weight:bold">Bank Name</th>
                            <th style="font-weight:bold">Staff Population</th>
                            <th style="font-weight:bold">Number of Registered Users</th>
                            <th style="font-weight:bold">Number of Staff Who Have Completed the Training</th>
                            <th style="font-weight:bold">Percentage Completed</th>
                        </tr>
                    </thead>
                </thead>
                    <tbody>
                        @if ($notEmpty)
                            @php $i = ($companies->currentPage() - 1) * $companies->perPage() + 1; @endphp
                            @foreach($companies as $company)
                                <tr class="tablerow">
                                    <td>{{ $i++}}</td>
                                    <td>{{ $company->title ?? '-' }}</td>
                                    <td>{{ $company->staff_number ?? '-' }}</td>
                                    <td>{{ $company->registered ?? '-' }}</td>
                                    <td>{{ $company->completed ?? '-'}}</td>
                                    <td>{{ round((int) $company->percentage) . '%' }} </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center"><strong>No items found.</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {!! $companies->links() !!}
            </div>
        </div>
    </div>
@stop
@push('extra_js')
<script src="{{ twillAsset('main-free.js') }}" crossorigin></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const exportButton = document.getElementById('exportButton');
        const searchTerm = "{{ $activeSearchTerm }}";
        const exportBaseUrl = "{{ route('twill.export.subsidiaryCompletions') }}";

        let queryString = '';
        const params = [];

        if (searchTerm) params.push(`search=${encodeURIComponent(searchTerm.trim())}`);
        if (params.length > 0) queryString = '?' + params.join('&');

        exportButton.addEventListener('click', function (event) {
            if (exportButton.disabled) {
                event.preventDefault();
                return;
            }

            event.preventDefault();
            window.location.href = exportBaseUrl + queryString;
        });

        document.querySelector('button[name="reset"]').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = "{{ route('twill.subsidiaryCompletion') }}";
        });
    });
</script>

@endpush
