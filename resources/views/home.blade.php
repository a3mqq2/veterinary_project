@extends('layouts.app')

@section('title', __('messages.dashboard'))
@section('skip-dashboard-analytics', true)

@section('content')
<div class="row">
    {{-- Welcome Section --}}
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white" style="background: linear-gradient(135deg, #1e6f6a 0%, #2a9d8f 100%) !important;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1 text-white">{{ __('messages.welcome_back') }}، {{ auth()->user()->name }}!</h4>
                        <p class="mb-0 opacity-75">{{ __('messages.dashboard_subtitle') }}</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="ti ti-chart-bar" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cases Statistics Cards --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-primary">
                            <i class="ti ti-clipboard-list text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $totalCases }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.total_cases') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-success">
                            <i class="ti ti-folder-open text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $openCases }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.open_cases') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-warning">
                            <i class="ti ti-clock text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $inProgressCases }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.in_progress_cases') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-secondary">
                            <i class="ti ti-folder-check text-secondary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $closedCases }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.closed_cases') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isAdmin)
    {{-- Admin Statistics Cards --}}
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-info">
                            <i class="ti ti-users text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $adminStats['totalUsers'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.total_users') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-danger">
                            <i class="ti ti-virus text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $adminStats['totalDiseases'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.total_diseases') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-primary">
                            <i class="ti ti-stethoscope text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $adminStats['totalSymptoms'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.total_symptoms') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avtar bg-light-success">
                            <i class="ti ti-map-pin text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $adminStats['totalRegions'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">{{ __('messages.total_regions') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Cases Status Chart --}}
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-chart-pie me-2"></i>
                    {{ __('messages.cases_by_status') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="casesStatusChart"></div>
            </div>
        </div>
    </div>

    {{-- Breed Distribution Chart --}}
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-paw me-2"></i>
                    {{ __('messages.breed_distribution') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="breedDistributionChart"></div>
            </div>
        </div>
    </div>

    {{-- Farm Type Distribution Chart --}}
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-building-warehouse me-2"></i>
                    {{ __('messages.farm_type_distribution') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="farmTypeChart"></div>
            </div>
        </div>
    </div>

    {{-- Monthly Cases Chart --}}
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-chart-line me-2"></i>
                    {{ __('messages.monthly_cases') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="monthlyCasesChart"></div>
            </div>
        </div>
    </div>

    {{-- Age Distribution Chart --}}
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-calendar me-2"></i>
                    {{ __('messages.age_distribution') }}
                </h5>
            </div>
            <div class="card-body">
                <div id="ageDistributionChart"></div>
            </div>
        </div>
    </div>

    {{-- Cases by Region --}}
    <div class="col-md-6 col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-map-pin me-2"></i>
                    {{ __('messages.cases_by_region') }}
                </h5>
            </div>
            <div class="card-body">
                @if($casesByRegion->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($casesByRegion as $regionCase)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>
                                    <i class="ti ti-map-pin text-muted me-2"></i>
                                    {{ $regionCase->region->localized_name ?? __('messages.unknown') }}
                                </span>
                                <span class="badge bg-primary rounded-pill">{{ $regionCase->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-map-pin-off fs-1 d-block mb-2"></i>
                        {{ __('messages.no_data_available') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Top Symptoms --}}
    <div class="col-md-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-stethoscope me-2"></i>
                    {{ __('messages.top_symptoms') }}
                </h5>
            </div>
            <div class="card-body">
                @if($topSymptoms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.symptom') }}</th>
                                    <th>{{ __('messages.category') }}</th>
                                    <th class="text-center">{{ __('messages.cases') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSymptoms as $index => $symptom)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $index < 3 ? 'primary' : 'secondary' }} rounded-pill">{{ $index + 1 }}</span>
                                        </td>
                                        <td>{{ app()->getLocale() === 'ar' ? $symptom->name_ar : $symptom->name_en }}</td>
                                        <td>
                                            @if($symptom->category === 'clinical_signs')
                                                <span class="badge bg-info">{{ __('messages.clinical_signs') }}</span>
                                            @elseif($symptom->category === 'pm_lesions')
                                                <span class="badge bg-warning">{{ __('messages.pm_lesions') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ __('messages.lab_findings') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-primary">{{ $symptom->cases_count }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-stethoscope-off fs-1 d-block mb-2"></i>
                        {{ __('messages.no_symptoms_data') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Top Diseases --}}
    <div class="col-md-12 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-virus me-2"></i>
                    {{ __('messages.top_diseases') }}
                </h5>
            </div>
            <div class="card-body">
                @if($topDiseases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.disease') }}</th>
                                    <th class="text-center">{{ __('messages.cases') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDiseases as $index => $disease)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $index < 3 ? 'danger' : 'secondary' }} rounded-pill">{{ $index + 1 }}</span>
                                        </td>
                                        <td>{{ app()->getLocale() === 'ar' ? $disease->name_ar : $disease->name_en }}</td>
                                        <td class="text-center">
                                            <span class="fw-bold text-danger">{{ $disease->cases_count }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-virus-off fs-1 d-block mb-2"></i>
                        {{ __('messages.no_diseases_data') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Average Flock Size Card --}}
    <div class="col-md-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-chart-bar me-2"></i>
                    {{ __('messages.quick_overview') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Average Flock Size --}}
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, rgba(30, 111, 106, 0.1) 0%, rgba(42, 157, 143, 0.1) 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-primary">
                                        <i class="ti ti-users-group text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ number_format($avgFlockSize ?? 0, 0) }}</h3>
                                    <p class="text-muted mb-0 small">{{ __('messages.avg_flock_size') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Symptoms Observed --}}
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(255, 107, 107, 0.1) 100%);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avtar bg-light-danger">
                                        <i class="ti ti-activity text-danger fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">{{ $topSymptoms->sum('cases_count') }}</h3>
                                    <p class="text-muted mb-0 small">{{ __('messages.symptoms') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Breed Stats --}}
                    @foreach($breedDistribution as $breed)
                    <div class="col-4">
                        <div class="text-center p-2 rounded bg-light">
                            <h4 class="mb-1 text-primary">{{ $breed->count }}</h4>
                            <small class="text-muted">
                                @if($breed->breed === 'local')
                                    {{ __('messages.breed_local') }}
                                @elseif($breed->breed === 'imported')
                                    {{ __('messages.breed_imported') }}
                                @else
                                    {{ __('messages.breed_mixed') }}
                                @endif
                            </small>
                        </div>
                    </div>
                    @endforeach

                    {{-- Farm Type Stats --}}
                    @foreach($farmTypeDistribution as $farmType)
                    <div class="col-4">
                        <div class="text-center p-2 rounded bg-light">
                            <h4 class="mb-1 text-success">{{ $farmType->count }}</h4>
                            <small class="text-muted">
                                @if($farmType->farm_type === 'extensive')
                                    {{ __('messages.extensive') }}
                                @elseif($farmType->farm_type === 'semi_intensive')
                                    {{ __('messages.semi_intensive') }}
                                @else
                                    {{ __('messages.intensive') }}
                                @endif
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Cases --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-clock me-2"></i>
                    {{ __('messages.recent_cases') }}
                </h5>
                <a href="{{ route('cases.index') }}" class="btn btn-sm btn-outline-primary">
                    {{ __('messages.view_all') }}
                    <i class="ti ti-arrow-left ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @if($recentCases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.case_number') }}</th>
                                    <th>{{ __('messages.owner_name') }}</th>
                                    <th>{{ __('messages.region') }}</th>
                                    <th>{{ __('messages.symptoms') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCases as $case)
                                    <tr>
                                        <td>
                                            <span class="fw-medium text-primary">{{ $case->case_number }}</span>
                                        </td>
                                        <td>{{ $case->owner_name ?? '-' }}</td>
                                        <td>
                                            @if($case->region)
                                                <span class="badge bg-light text-dark">
                                                    <i class="ti ti-map-pin me-1"></i>
                                                    {{ $case->region->localized_name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $case->symptoms->count() }}</span>
                                        </td>
                                        <td>
                                            @if($case->status === 'open')
                                                <span class="badge bg-success">{{ __('messages.status_open') }}</span>
                                            @elseif($case->status === 'in_progress')
                                                <span class="badge bg-warning">{{ __('messages.status_in_progress') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('messages.status_closed') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $case->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('cases.show', $case) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-clipboard-off fs-1 d-block mb-2"></i>
                        {{ __('messages.no_cases') }}
                        <div class="mt-3">
                            <a href="{{ route('cases.create') }}" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                                <i class="ti ti-plus me-1"></i>
                                {{ __('messages.add_case') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data from PHP
    var breedData = [
        @foreach($breedDistribution as $item)
            { breed: '{{ $item->breed }}', count: {{ (int)$item->count }} },
        @endforeach
    ];

    var farmTypeData = [
        @foreach($farmTypeDistribution as $item)
            { farm_type: '{{ $item->farm_type }}', count: {{ (int)$item->count }} },
        @endforeach
    ];

    var monthlyData = [
        @foreach($monthlyCases as $item)
            { month: {{ (int)$item->month }}, count: {{ (int)$item->count }} },
        @endforeach
    ];

    var ageData = [
        @foreach($ageDistribution as $item)
            { age_group: '{{ $item->age_group }}', count: {{ (int)$item->count }} },
        @endforeach
    ];

    var openCases = {{ (int)$openCases }};
    var inProgressCases = {{ (int)$inProgressCases }};
    var closedCases = {{ (int)$closedCases }};

    // Cases Status Pie Chart
    try {
        var statusEl = document.querySelector("#casesStatusChart");
        if (statusEl && (openCases > 0 || inProgressCases > 0 || closedCases > 0)) {
            var casesStatusChart = new ApexCharts(statusEl, {
                series: [openCases, inProgressCases, closedCases],
                chart: { type: 'pie', height: 250 },
                labels: ['{{ __('messages.status_open') }}', '{{ __('messages.status_in_progress') }}', '{{ __('messages.status_closed') }}'],
                colors: ['#198754', '#ffc107', '#6c757d'],
                legend: { position: 'bottom' }
            });
            casesStatusChart.render();
        } else if (statusEl) {
            statusEl.innerHTML = '<div class="text-center text-muted py-5">{{ __('messages.no_data_available') }}</div>';
        }
    } catch(e) { console.log('Status chart error:', e); }

    // Breed Distribution Chart
    try {
        var breedEl = document.querySelector("#breedDistributionChart");
        var breedLabels = { 'local': '{{ __('messages.breed_local') }}', 'imported': '{{ __('messages.breed_imported') }}', 'mixed': '{{ __('messages.breed_mixed') }}' };
        var breedSeries = [], breedNames = [];
        breedData.forEach(function(item) {
            if (item.breed && item.count > 0) {
                breedSeries.push(item.count);
                breedNames.push(breedLabels[item.breed] || item.breed);
            }
        });

        if (breedEl && breedSeries.length > 0) {
            var breedChart = new ApexCharts(breedEl, {
                series: breedSeries,
                chart: { type: 'pie', height: 250 },
                labels: breedNames,
                colors: ['#1e6f6a', '#2a9d8f', '#e9c46a'],
                legend: { position: 'bottom' }
            });
            breedChart.render();
        } else if (breedEl) {
            breedEl.innerHTML = '<div class="text-center text-muted py-5">{{ __('messages.no_data_available') }}</div>';
        }
    } catch(e) { console.log('Breed chart error:', e); }

    // Farm Type Distribution Chart
    try {
        var farmEl = document.querySelector("#farmTypeChart");
        var farmTypeLabels = { 'extensive': '{{ __('messages.extensive') }}', 'semi_intensive': '{{ __('messages.semi_intensive') }}', 'intensive': '{{ __('messages.intensive') }}' };
        var farmTypeSeries = [], farmTypeNames = [];
        farmTypeData.forEach(function(item) {
            if (item.farm_type && item.count > 0) {
                farmTypeSeries.push(item.count);
                farmTypeNames.push(farmTypeLabels[item.farm_type] || item.farm_type);
            }
        });

        if (farmEl && farmTypeSeries.length > 0) {
            var farmTypeChart = new ApexCharts(farmEl, {
                series: farmTypeSeries,
                chart: { type: 'pie', height: 250 },
                labels: farmTypeNames,
                colors: ['#264653', '#2a9d8f', '#f4a261'],
                legend: { position: 'bottom' }
            });
            farmTypeChart.render();
        } else if (farmEl) {
            farmEl.innerHTML = '<div class="text-center text-muted py-5">{{ __('messages.no_data_available') }}</div>';
        }
    } catch(e) { console.log('Farm type chart error:', e); }

    // Monthly Cases Line Chart
    try {
        var monthlyEl = document.querySelector("#monthlyCasesChart");
        var monthNames = ['{{ __('messages.jan') }}', '{{ __('messages.feb') }}', '{{ __('messages.mar') }}', '{{ __('messages.apr') }}', '{{ __('messages.may') }}', '{{ __('messages.jun') }}', '{{ __('messages.jul') }}', '{{ __('messages.aug') }}', '{{ __('messages.sep') }}', '{{ __('messages.oct') }}', '{{ __('messages.nov') }}', '{{ __('messages.dec') }}'];
        var categories = [], seriesData = [];
        monthlyData.forEach(function(item) {
            if (item.month >= 1 && item.month <= 12) {
                categories.push(monthNames[item.month - 1]);
                seriesData.push(item.count);
            }
        });

        if (monthlyEl && seriesData.length > 0) {
            var monthlyCasesChart = new ApexCharts(monthlyEl, {
                series: [{ name: '{{ __('messages.cases') }}', data: seriesData }],
                chart: { type: 'line', height: 250, toolbar: { show: false } },
                colors: ['#1e6f6a'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 4 },
                xaxis: { categories: categories },
                yaxis: { min: 0 }
            });
            monthlyCasesChart.render();
        } else if (monthlyEl) {
            monthlyEl.innerHTML = '<div class="text-center text-muted py-5">{{ __('messages.no_data_available') }}</div>';
        }
    } catch(e) { console.log('Monthly chart error:', e); }

    // Age Distribution Chart
    try {
        var ageEl = document.querySelector("#ageDistributionChart");
        var ageLabels = { '< 1 year': '{{ __('messages.less_than_1_year') }}', '1 year': '{{ __('messages.1_year') }}', '2 years': '{{ __('messages.2_years') }}', '3 years': '{{ __('messages.3_years') }}', '4+ years': '{{ __('messages.4_plus_years') }}' };
        var ageCategories = [], ageSeries = [];
        ageData.forEach(function(item) {
            if (item.age_group && item.count > 0) {
                ageCategories.push(ageLabels[item.age_group] || item.age_group);
                ageSeries.push(item.count);
            }
        });

        if (ageEl && ageSeries.length > 0) {
            var ageChart = new ApexCharts(ageEl, {
                series: [{ name: '{{ __('messages.cases') }}', data: ageSeries }],
                chart: { type: 'bar', height: 250, toolbar: { show: false } },
                colors: ['#e76f51'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
                xaxis: { categories: ageCategories },
                yaxis: { min: 0 }
            });
            ageChart.render();
        } else if (ageEl) {
            ageEl.innerHTML = '<div class="text-center text-muted py-5">{{ __('messages.no_data_available') }}</div>';
        }
    } catch(e) { console.log('Age chart error:', e); }
});
</script>
@endpush

<style>
.avtar {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}
.bg-light-primary { background-color: rgba(30, 111, 106, 0.1) !important; }
.bg-light-success { background-color: rgba(25, 135, 84, 0.1) !important; }
.bg-light-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
.bg-light-danger { background-color: rgba(220, 53, 69, 0.1) !important; }
.bg-light-info { background-color: rgba(13, 202, 240, 0.1) !important; }
.bg-light-secondary { background-color: rgba(108, 117, 125, 0.1) !important; }
.text-primary { color: #1e6f6a !important; }
</style>
@endsection
