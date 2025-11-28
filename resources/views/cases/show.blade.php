@extends('layouts.app')

@section('title', $case->case_number)

@section('content')
<style>
    .case-header {
        background: linear-gradient(135deg, #1e6f6a 0%, #2a9d8f 100%);
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .case-number {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }
    .status-open { background: #10b981; }
    .status-in_progress { background: #f59e0b; }
    .status-closed { background: #6b7280; }

    .info-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .info-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        font-weight: 600;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }
    .info-header i {
        color: #1e6f6a;
        margin-inline-end: 0.75rem;
        font-size: 1.2rem;
    }
    .info-body {
        padding: 1.5rem;
    }
    .info-row {
        display: flex;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f1f3f4;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 500;
        color: #6c757d;
        min-width: 150px;
    }
    .info-value {
        color: #212529;
    }

    /* Probable Diseases Section */
    .diseases-section {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .diseases-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #92400e;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .diseases-title i {
        margin-inline-end: 0.75rem;
    }
    .disease-card {
        background: white;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .disease-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .disease-card:last-child {
        margin-bottom: 0;
    }
    .disease-rank {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .rank-1 { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; }
    .rank-2 { background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%); color: white; }
    .rank-3 { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: white; }
    .rank-other { background: #e5e7eb; color: #374151; }
    .disease-info {
        flex: 1;
        margin-inline-start: 1rem;
    }
    .disease-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: #1f2937;
    }
    .disease-meta {
        font-size: 0.9rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    .match-percentage {
        font-size: 1.5rem;
        font-weight: 700;
        text-align: end;
    }
    .match-high { color: #059669; }
    .match-medium { color: #d97706; }
    .match-low { color: #dc2626; }
    .progress-bar-container {
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        margin-top: 0.5rem;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }
    .progress-high { background: linear-gradient(90deg, #10b981, #059669); }
    .progress-medium { background: linear-gradient(90deg, #fbbf24, #d97706); }
    .progress-low { background: linear-gradient(90deg, #f87171, #dc2626); }

    .no-diseases {
        text-align: center;
        padding: 2rem;
        color: #92400e;
    }

    /* Symptoms Display */
    .symptoms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }
    .symptom-tag {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }
    .symptom-tag i {
        margin-inline-end: 0.5rem;
        color: #16a34a;
    }
    .symptom-tag.clinical { background: #eff6ff; border-color: #93c5fd; }
    .symptom-tag.clinical i { color: #2563eb; }
    .symptom-tag.pm { background: #fefce8; border-color: #fde047; }
    .symptom-tag.pm i { color: #ca8a04; }
    .symptom-tag.lab { background: #ecfeff; border-color: #67e8f9; }
    .symptom-tag.lab i { color: #0891b2; }

    .matching-symptom {
        background: #fef3c7 !important;
        border-color: #fbbf24 !important;
    }
</style>

<div class="row">
    <div class="col-12">
        {{-- Header --}}
        <div class="case-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="case-number">{{ $case->case_number }}</div>
                    <div class="mt-2 opacity-75">
                        <i class="ti ti-calendar me-1"></i>
                        {{ $case->created_at->format('Y-m-d H:i') }}
                        @if($case->user)
                            <span class="mx-2">|</span>
                            <i class="ti ti-user me-1"></i>
                            {{ $case->user->name }}
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    {{-- Status Dropdown --}}
                    <div class="dropdown">
                        <button class="btn status-badge status-{{ $case->status }} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $case->getStatusLabel() }}
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="{{ route('cases.update-status', $case) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="open">
                                    <button type="submit" class="dropdown-item {{ $case->status == 'open' ? 'active' : '' }}">
                                        <i class="ti ti-circle-check text-success me-2"></i>
                                        {{ __('messages.status_open') }}
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('cases.update-status', $case) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="dropdown-item {{ $case->status == 'in_progress' ? 'active' : '' }}">
                                        <i class="ti ti-clock text-warning me-2"></i>
                                        {{ __('messages.status_in_progress') }}
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('cases.update-status', $case) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" class="dropdown-item {{ $case->status == 'closed' ? 'active' : '' }}">
                                        <i class="ti ti-circle-x text-secondary me-2"></i>
                                        {{ __('messages.status_closed') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('cases.print-show', $case) }}" class="btn btn-light" target="_blank">
                        <i class="ti ti-printer me-1"></i>
                        {{ __('messages.print') }}
                    </a>
                    <a href="{{ route('cases.edit', $case) }}" class="btn btn-light">
                        <i class="ti ti-edit me-1"></i>
                        {{ __('messages.edit') }}
                    </a>
                    <a href="{{ route('cases.index') }}" class="btn btn-outline-light">
                        <i class="ti ti-arrow-right me-1"></i>
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>

        @include('layouts.messages')
    </div>
</div>

{{-- Probable Diseases Section --}}
@if($probableDiseases->count() > 0)
<div class="diseases-section">
    <div class="diseases-title">
        <i class="ti ti-virus"></i>
        {{ __('messages.probable_diseases') }}
    </div>
    <div class="row">
        @foreach($probableDiseases as $index => $item)
            @php
                $percentage = $item['percentage'];
                $matchClass = $percentage >= 70 ? 'high' : ($percentage >= 40 ? 'medium' : 'low');
                $rankClass = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other'));
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="disease-card">
                    <div class="d-flex align-items-start">
                        <div class="disease-rank {{ $rankClass }}">{{ $index + 1 }}</div>
                        <div class="disease-info">
                            <div class="disease-name">
                                {{ app()->getLocale() == 'ar' ? $item['disease']->name_ar : $item['disease']->name_en }}
                            </div>
                            <div class="disease-meta">
                                {{ __('messages.matching_symptoms') }}: {{ $item['match_count'] }} / {{ $item['total_disease_symptoms'] }}
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill progress-{{ $matchClass }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <div class="match-percentage match-{{ $matchClass }}">
                            {{ $percentage }}%
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="diseases-section">
    <div class="diseases-title">
        <i class="ti ti-virus"></i>
        {{ __('messages.probable_diseases') }}
    </div>
    <div class="no-diseases">
        <i class="ti ti-mood-empty fs-1 d-block mb-2"></i>
        {{ __('messages.no_probable_diseases') }}
    </div>
</div>
@endif

<div class="row">
    {{-- Left Column --}}
    <div class="col-lg-6">
        {{-- Farm Owner Information --}}
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-user"></i>
                {{ __('messages.farm_owner_info') }}
            </div>
            <div class="info-body">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.owner_name') }}</span>
                    <span class="info-value">{{ $case->owner_name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.owner_phone') }}</span>
                    <span class="info-value">{{ $case->owner_phone ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Farm Information --}}
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-building-farm"></i>
                {{ __('messages.farm_info') }}
            </div>
            <div class="info-body">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.farm_location') }}</span>
                    <span class="info-value">{{ $case->farm_location ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.farm_type') }}</span>
                    <span class="info-value">{{ $case->getFarmTypeLabel() ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.flock_size') }}</span>
                    <span class="info-value">{{ $case->flock_size ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.other_animals') }}</span>
                    <span class="info-value">{{ $case->other_animals ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Animal Information --}}
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-pig"></i>
                {{ __('messages.animal_info') }}
            </div>
            <div class="info-body">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.age') }}</span>
                    <span class="info-value">{{ $case->getFormattedAge() ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.breed') }}</span>
                    <span class="info-value">{{ $case->getBreedLabel() ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.milking_ewes') }}</span>
                    <span class="info-value">{{ $case->milking_ewes ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.dry_ewes') }}</span>
                    <span class="info-value">{{ $case->dry_ewes ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-6">
        {{-- Nutrition Program for Milking Ewes --}}
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-leaf"></i>
                {{ __('messages.nutrition_milking') }}
            </div>
            <div class="info-body">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.feed_type') }}</span>
                    <span class="info-value">{{ $case->milking_feed_type ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.daily_consumption') }}</span>
                    <span class="info-value">{{ $case->milking_daily_consumption ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.feeding_schedule') }}</span>
                    <span class="info-value">{{ $case->milking_feeding_schedule ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.mineral_vitamin') }}</span>
                    <span class="info-value">
                        @if($case->milking_mineral_vitamin)
                            <span class="badge bg-success">{{ __('messages.provided') }}</span>
                        @elseif($case->milking_mineral_vitamin === false)
                            <span class="badge bg-secondary">{{ __('messages.not_provided') }}</span>
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Nutrition Program for Dry Ewes --}}
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-plant"></i>
                {{ __('messages.nutrition_dry') }}
            </div>
            <div class="info-body">
                <p class="mb-0">{{ $case->dry_ewes_nutrition ?? '-' }}</p>
            </div>
        </div>

        {{-- Lambs Health --}}
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-heart"></i>
                {{ __('messages.lambs_health') }}
            </div>
            <div class="info-body">
                <p class="mb-0">{{ $case->lambs_health_problems ?? '-' }}</p>
            </div>
        </div>

        {{-- Vaccination & Medication --}}
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-vaccine"></i>
                {{ __('messages.vaccination_medication') }}
            </div>
            <div class="info-body">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.vaccination_history') }}</span>
                    <span class="info-value">{{ $case->vaccination_history ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.medication_programs') }}</span>
                    <span class="info-value">{{ $case->medication_programs ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($case->notes)
        <div class="info-card">
            <div class="info-header">
                <i class="ti ti-notes"></i>
                {{ __('messages.notes') }}
            </div>
            <div class="info-body">
                <p class="mb-0">{{ $case->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Selected Symptoms --}}
<div class="info-card">
    <div class="info-header">
        <i class="ti ti-stethoscope"></i>
        {{ __('messages.selected_symptoms') }} ({{ $case->symptoms->count() }})
    </div>
    <div class="info-body">
        @if($case->symptoms->count() > 0)
            @php
                $clinicalSymptoms = $case->symptoms->where('category', 'clinical_signs');
                $pmSymptoms = $case->symptoms->where('category', 'pm_lesions');
                $labSymptoms = $case->symptoms->where('category', 'lab_findings');
            @endphp

            @if($clinicalSymptoms->count() > 0)
                <h6 class="mb-3"><i class="ti ti-stethoscope me-2" style="color: #2563eb;"></i>{{ __('messages.clinical_signs') }}</h6>
                <div class="symptoms-grid mb-4">
                    @foreach($clinicalSymptoms as $symptom)
                        <div class="symptom-tag clinical">
                            <i class="ti ti-check"></i>
                            {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                        </div>
                    @endforeach
                </div>
            @endif

            @if($pmSymptoms->count() > 0)
                <h6 class="mb-3"><i class="ti ti-microscope me-2" style="color: #ca8a04;"></i>{{ __('messages.pm_lesions') }}</h6>
                <div class="symptoms-grid mb-4">
                    @foreach($pmSymptoms as $symptom)
                        <div class="symptom-tag pm">
                            <i class="ti ti-check"></i>
                            {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                        </div>
                    @endforeach
                </div>
            @endif

            @if($labSymptoms->count() > 0)
                <h6 class="mb-3"><i class="ti ti-flask me-2" style="color: #0891b2;"></i>{{ __('messages.lab_findings') }}</h6>
                <div class="symptoms-grid">
                    @foreach($labSymptoms as $symptom)
                        <div class="symptom-tag lab">
                            <i class="ti ti-check"></i>
                            {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center text-muted py-4">
                <i class="ti ti-mood-empty fs-1 d-block mb-2"></i>
                {{ __('messages.no_symptoms_selected') }}
            </div>
        @endif
    </div>
</div>
@endsection
