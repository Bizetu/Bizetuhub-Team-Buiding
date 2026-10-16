<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Snapshot - {{ $report['user']->first_name }} {{ $report['user']->last_name }}</title>
    <style>
        @font-face {
            font-family: 'Aptos';
            src: url("fonts/Aptos.ttf") format('truetype');
        }

        @font-face {
            font-family: 'Aptos-Black';
            src: url("fonts/Aptos-Black.ttf") format('truetype');
        }

        @font-face {
            font-family: 'Aptos-Bold';
            src: url("fonts/Aptos-Bold.ttf") format('truetype');
        }

        @font-face {
            font-family: 'Aptos-Light';
            src: url("fonts/Aptos-Light.ttf") format('truetype');
        }

        @font-face {
            font-family: 'Aptos-Mono';
            src: url("fonts/Aptos-Mono.ttf") format('truetype');
        }

        @font-face {
            font-family: 'Aptos-Regular';
            src: url("fonts/Aptos.ttf") format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Aptos';
            font-size: 12px;
            color: #000;
            padding: 20px;
            line-height: 1.4;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-left {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
        }

        .header-left img {
            width: 100px;
            height: auto;
            display: block;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 12px;
            padding-left: 20px;
        }

        .header-right strong {
            font-size: 13px;
            display: block;
            margin-bottom: 2px;
        }

        .user-bar {
            border: 1px solid #000;
            padding: 8px 12px;
            margin-bottom: 15px;
        }

        .user-bar table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-bar td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .user-bar .left-col {
            width: 48%;
            padding-right: 15px;
        }

        .user-bar .right-col {
            width: 48%;
            padding-left: 15px;
            border-left: 1px solid #ddd;
        }

        .user-bar div {
            margin-bottom: 4px;
            line-height: 1.5;
        }

        .user-bar strong {
            display: inline-block;
            width: 95px;
            font-weight: 600;
        }

        .columns {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .column {
            display: table-cell;
            width: 33.33%;
            padding: 0 8px;
            vertical-align: top;
        }

        .column:first-child {
            padding-left: 0;
        }

        .column:last-child {
            padding-right: 0;
        }

        h2 {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-top: 5px;
        }

        th,
        td {
            padding: 4px 6px;
            text-align: left;
            border: 1px solid #000;
            font-size: 12px;
        }

        th {
            font-weight: 600;
            background: #f5f5f5;
        }

        td {
            line-height: 1.3;
        }

        .empty {
            text-align: center;
            padding: 20px;
            font-style: italic;
            border: 1px solid #000;
            margin-top: 5px;
            background: #fafafa;
        }

        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        th,
        .header-right {
            font-family: 'DejaVu Sans';
            font-weight: bold;
        }

    </style>
</head>

<body>
    <div class="header">
        <div class="header-left">
            <img src="{{ $report['logo'] }}" alt="AKI Logo" />
        </div>
        <div class="header-right">
            <span> <strong>My Course Uptake Progress On the RBA Academy</strong></span>
            <span style="font-weight: normal">{{ $report['date'] }}</span>
        </div>
    </div>

    <div class="row">
        <div class="user-bar">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding-right: 20px; border:1px solid black;  padding:5px 10px">
                        <table style="width:100%; border:none;">
                            <tr>
                                <td style="padding: 3px 0; font-weight:bold;">Name:</td>
                                <td style="padding: 3px 0;">{{ $report['user']->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight:bold;">Email:</td>
                                <td style="padding: 3px 0;">{{ $report['user']->email }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight:bold;">Job Role:</td>
                                <td style="padding: 3px 0;">{{ $report['jobrole'] }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight:bold;">Company:</td>
                                <td style="padding: 3px 0;">{{ $report['company'] }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; border:1px solid black; padding:5px 10px">
                        <table style="width:100%; border:none;">
                            <tr>
                                <td style="padding: 3px 0; font-weight:bold;">Total Duration:</td>
                                <td style="padding: 3px 0; text-align:right;">{{ $report['effort_hours'] }} hrs</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight:bold;">Completed Modules:</td>
                                <td style="padding: 3px 0; text-align:right;">{{ $report['completions']->count() }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight:bold;">Required Modules:</td>
                                <td style="padding: 3px 0; text-align:right;">{{ $report['mandatory']->count() }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <h2>Completed Modules</h2>
            @if ($report['mandatory']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width:15px; font-size:20px;">#</th>
                            <th>Course</th>
                            <th style="width:30px">Required</th>
                            <th style="width:35px">Score</th>
                            <th style="width:55px">Status</th>
                            <th style="width:20px">Hrs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['mandatory'] as $course)
                            <tr>
                                <td>{{ $report['index']++ }}</td>
                                <td>{{ $course->course ?? '-' }}</td>
                                <td style="font-family: DejaVu Sans; text-align:center; font-size: 18px;">
                                    {{ '✓' }}
                                </td>
                                <td>{{ round($course->score * 100, 2) }}%</td>
                                <td>
                                    {{ 
                                        isset($course->completion_status) 
                                            ? $course->completion_status 
                                            : ($course->license_id ? 'In Progress' : 'Yet Started') 
                                    }}
                                </td>
                                <td>{{ $course->effort_hours }}</td>
                                {{-- <td>{{ (float)$course->cpd_points }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty">Current Course List is Empty</p>
            @endif
        </div>

        {{-- <div class="column">
            <h2>Compulsory Modules</h2>
            @if ($report['mandatory']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width:20px">#</th>
                            <th>Course</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['mandatory'] as $compulsory)
                            <tr>
                                <td>{{ $report['index_mandatory']++ }}</td>
                                <td>{{ $compulsory->course ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty">No compulsory modules</p>
            @endif
        </div> --}}


    </div>

    {{-- <div class="columns">
        <div class="column">
            <h2>Enrolled Modules</h2>
            @if ($report['licenses']->count() > 0)
                <table style="width: auto">
                    <thead>
                        <tr>
                            <th style="width:20px">#</th>
                            <th>Course</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['licenses'] as $license)
                            <tr>
                                <td>{{ $report['enrolled_index']++ }}</td>
                                <td>{{ $license->course_title ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="empty">No enrolled modules</p>
            @endif
        </div>
    </div> --}}
</body>

</html>
