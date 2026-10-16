@php
    use App\Models\CompletionReport;
    use App\Models\UserLicense;
    use App\Models\User;
    use Carbon\Carbon;

    $years = range('2025', now()->year);
    $month = [];
    for ($m = 1; $m <= 12; $m++) {
        $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
    }
    $user = auth()->user();
    $query = User::where('id', '>', 1);

    if ($user->role_value !== 'SUPERADMIN' && !empty($user->company_id)) {
        $query->where('company_id', $user->company_id);
    }

    $totalStaff = (clone $query)->count();
    $completedCount = (clone $query)->where('completion_status', 1)->count();
    $licensesQuery = UserLicense::query();

    if ($user->role_value !== 'SUPERADMIN') {
        $licensesQuery->whereHas('user', function ($q) use ($user) {
            $q->where('company_id', $user->company_id);
        });
    }

    $licenses = $licensesQuery->count();

    $completionsQuery = CompletionReport::query();

    if ($user->role_value !== 'SUPERADMIN') {
        $completionsQuery->where('company_id', $user->company_id);
    }

    $completions = $completionsQuery->count();
@endphp

@extends('twill::layouts.free')

@push('extra_css')
    <link href="{{ asset('css/reports.css') }}" rel="stylesheet">
    <link href="{{ asset('css/twill/reports.css') }}" rel="stylesheet">

    <style>
        .dashboard-container {
            padding: 24px 0;
        }
        .dashboard-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .stat-card {
            background: #ffffff;
            padding: 18px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            cursor: pointer;
        }
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .stat-title {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }
        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
        }
        .stat-value {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
    </style>
@endpush

@section('customPageContent')
    <div class="dashboard-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="dashboard-container">
                    <h1 class="dashboard-title">Bank Dashboard Report</h1>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <a href="{{ route('twill.users.index') }}" style="text-decoration: none;">
                                <div class="stat-header">
                                    <span class="stat-title">Total Staff Registered</span>
                                    <div class="stat-icon">
                                        <svg width="18" height="18" fill="none" stroke="#000000" stroke-width="2">
                                            <circle cx="9" cy="6" r="3"/>
                                            <path d="M2 16c1.5-3 12.5-3 14 0"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="stat-value">{{ number_format($totalStaff) }}</div>
                            </a>
                        </div>
                        <div class="stat-card">
                            <a href="{{ route('twill.userLicenses.index') }}" style="text-decoration: none;">
                                <div class="stat-header">
                                    <span class="stat-title">Total Enrollments</span>
                                    <div class="stat-icon">
                                        <svg width="18" height="18" fill="none" stroke="#000000" stroke-width="2">
                                            <path d="M3 4h10a2 2 0 012 2v8H5a2 2 0 00-2 2V4z"/>
                                            <path d="M5 4v12"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="stat-value">{{ $licenses }}</div>
                            </a>
                        </div>
                        <div class="stat-card">
                            <a href="{{ route('twill.completionReports.index') }}" style="text-decoration: none;">
                                <div class="stat-header">
                                    <span class="stat-title">Total Module Completions</span>
                                    <div class="stat-icon">
                                        <svg width="18" height="18" fill="none" stroke="#000000" stroke-width="2">
                                            <path d="M4 10l3 3 7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="stat-value">{{ number_format($completions) }}</div>
                            </a>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <span class="stat-title">Overall Programme Completion</span>
                                <div class="stat-icon">
                                    <svg width="18" height="18" fill="none" stroke="#000000" stroke-width="2">
                                        <path d="M3 17h12"/>
                                        <path d="M6 13V9"/>
                                        <path d="M10 13V5"/>
                                        <path d="M14 13v-3"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="stat-value">{{ number_format($completedCount) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Login Trends --}}
            <div class="col-6">
                <div class="report_visual" id="registrationDistribution" style="height: 500px">
                    <div class="chart-loading">Loading chart...</div>
                </div>
            </div>

            {{-- User Distribution --}}
            <div class="col-6">
                <div class="report_visual" id="userDistribution" style="height: 500px">
                    <div class="chart-loading">Loading chart...</div>
                </div>
            </div>

            {{-- Yearly Completion --}}
            <div class="col-6">
                <div class="filter-section">
                    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <label style="color: #000; margin: 0;">Filter by year</label>
                        <select name="year" id="completion-year" class="form-control" style="width: auto;">
                            <option value="0">To date</option>
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ $y == Carbon::now()->format('Y') ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="report_visual" id="completion_yearly_container" style="height: 450px">
                        <div class="chart-loading">Loading chart...</div>
                    </div>
                </div>
            </div>

            {{-- Gender Distribution --}}
            <div class="col-6">
                <div class="report_visual" id="genderDistribution" style="height: 500px">
                    <div class="chart-loading">Loading chart...</div>
                </div>
            </div>

            {{-- Top Users --}}
            <div class="col-6">
                <div class="stats-table">
                    <div class="row vm-4 hp-4" style="align-items: center;">
                        <div class="col-9">
                            <h3 class="hp-4">Top 10 users with most completed courses</h3>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <a href="{{ route('twill.completionReports.index') }}" class="secondary-yellow-button vp-2 hp-2">View More</a>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: start;">Name</th>
                                <th style="text-align: center;">Number of completed courses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($userCompletions as $value)
                                <tr>
                                    <td style="text-align: start;">{{ $value['name'] }}</td>
                                    <td style="text-align: center;">{{ $value['total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #6c757d;">No data available</td>
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
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/modules/data.js') }}"></script>
    <script src="{{ asset('js/highcharts/modules/accessibility.js') }}"></script>
    <script src="{{ asset('js/highcharts/modules/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/modules/export-data.js') }}"></script>
    <script src="{{ asset('js/highcharts/lib/jspdf.js') }}"></script>
    <script src="{{ asset('js/highcharts/lib/svg2pdf.js') }}"></script>
    <script src="{{ asset('js/highcharts/modules/offline-exporting.js') }}"></script>

    <script>
        (function() {
            function initCharts() {
                if (typeof Highcharts === 'undefined') {
                    console.error('Highcharts failed to load!');
                    document.querySelectorAll('.chart-loading').forEach(el => {
                        el.textContent = 'Failed to load charts. Please refresh the page.';
                        el.style.color = '#dc3545';
                    });
                    return;
                }

                console.log('Highcharts loaded successfully, version:', Highcharts.version);

                // Login Trends
                try {
                    const loginData = [@foreach ($lastLoginOption as $value){{ $value }},@endforeach];

                    Highcharts.chart('registrationDistribution', {
                        chart: {
                            type: 'spline',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: 'Login Trends {{ now()->year }}',
                        },
                        subtitle: {
                            text: 'Logins per month'
                        },
                        xAxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                        },
                        yAxis: {
                            title: { text: 'Number of Logins' }
                        },
                        tooltip: {
                            crosshairs: true,
                            shared: true
                        },
                        plotOptions: {
                            spline: {
                                marker: {
                                    radius: 4,
                                    lineWidth: 1
                                }
                            }
                        },
                        series: [{
                            name: 'Login Trends',
                            color: '#1F3B67',
                            data: loginData
                        }],
                        credits: { enabled: false }
                    });
                } catch (error) {
                    console.error('Login Trends error:', error);
                }

                // User Distribution
                try {
                    const userData = [
                        @foreach ($completionChartOptions['series_data'] as $value)
                            {
                                name: '{{ $value['name'] }}',
                                y: {{ $value['total'] }},
                                color: '{{ $value['name'] == 'Completed Modules' ? '#1F3B67' : '#FFB901' }}'
                            },
                        @endforeach
                    ];

                    Highcharts.chart('userDistribution', {
                        chart: {
                            type: 'pie',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: '{{ $completionChartOptions['chart_title'] }}',
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Total: {point.y}'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f}%<br>Total: {point.y}'
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Users',
                            colorByPoint: true,
                            data: userData
                        }],
                        credits: { enabled: false }
                    });
                } catch (error) {
                    console.error('User Distribution error:', error);
                }

                // Yearly Completion
                let completionYearly;
                try {
                    const yearlyData = [
                        @foreach ($yearlyCompletionGraph['completion_data'] as $value)
                            { name: '{{ $value['name'] }}', y: {{ $value['total'] }} },
                        @endforeach
                    ];

                    completionYearly = Highcharts.chart('completion_yearly_container', {
                        chart: {
                            type: 'column',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: '{{ $yearlyCompletionGraph['chart_title'] }}',
                        },
                        plotOptions: {
                            series: {
                                grouping: false,
                                borderWidth: 0
                            }
                        },
                        legend: { enabled: false },
                        tooltip: {
                            shared: true,
                            headerFormat: '<span>{point.point.name}</span><br/>',
                            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
                        },
                        xAxis: {
                            type: 'category',
                            accessibility: { description: 'Courses' },
                            title: { text: 'Courses' },
                        },
                        yAxis: [{ title: { text: 'Completions' }, showFirstLabel: false }],
                        series: [{
                            color: '#1F3B67',
                            pointPlacement: -0.2,
                            linkedTo: 'main',
                            data: yearlyData,
                            name: 'Completions',
                            dataSorting: {
                                enabled: true,
                                matchByName: true
                            },
                        }],
                        credits: { enabled: false }
                    });

                    // Year filter
                    const yearSelect = document.getElementById('completion-year');
                    if (yearSelect) {
                        yearSelect.addEventListener('change', function(e) {
                            const year = e.target.value;
                            const term = year == 0 ? 'to date' : year;
                            const url = '{{ route('twill.graph.yearlyCRresults') }}?cyear=' + year;

                            fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const chartData = data.map(item => ({
                                    name: item.name,
                                    y: parseInt(item.total)
                                }));

                                completionYearly.update({
                                    title: { text: `Top 5 Course Completions of ${term}` },
                                    series: [{
                                        color: '#1F3B67',
                                        data: chartData,
                                        name: 'Completions'
                                    }]
                                }, true, false, { duration: 800 });
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                            });
                        });
                    }
                } catch (error) {
                    console.error('Yearly Completion error:', error);
                }

                // Gender Distribution
                try {
                    const genderData = [
                        @foreach ($genderChartOptions['series_data'] as $value)
                            {
                                name: '{{ $value['name'] }}',
                                y: {{ $value['total'] }},
                                color: '{{ $value['name'] == 'Female Users' ? '#1F3B67' : ($value['name'] == 'Male Users' ? '#FFB901' : '#000000') }}'
                            },
                        @endforeach
                    ];

                    Highcharts.chart('genderDistribution', {
                        chart: {
                            type: 'pie',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: '{{ $genderChartOptions['chart_title'] }}',
                        },
                        subtitle: {
                            text: '{{ $genderChartOptions['subtitle'] }}'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Total: {point.y}'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f}%<br>Total: {point.y}'
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Users',
                            colorByPoint: true,
                            data: genderData
                        }],
                        credits: { enabled: false }
                    });
                } catch (error) {
                    console.error('Gender Distribution error:', error);
                }

                console.log('charts initialized');
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCharts);
            } else {
                initCharts();
            }
        })();
    </script>
@endpush
