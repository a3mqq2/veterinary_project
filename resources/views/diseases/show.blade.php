@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? $disease->name_ar : $disease->name_en)

@section('content')
<div class="row">
    <div class="col-12">
        {{-- Header Card --}}
        <div class="card mb-4" style="border-top: 4px solid #1e6f6a;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-1" style="color: #1e6f6a;">
                            {{ app()->getLocale() == 'ar' ? $disease->name_ar : $disease->name_en }}
                        </h3>
                        <p class="text-muted mb-2">
                            {{ app()->getLocale() == 'ar' ? $disease->name_en : $disease->name_ar }}
                        </p>
                        <span class="badge {{ $disease->is_active ? 'bg-success' : 'bg-danger' }} fs-6">
                            {{ $disease->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('diseases.edit', $disease) }}" class="btn btn-outline-primary me-2">
                            <i class="ti ti-edit me-1"></i>
                            {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('diseases.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i>
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                @if($disease->description_en || $disease->description_ar)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">{{ __('messages.description_en') }}</h6>
                            <p>{{ $disease->description_en ?: '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">{{ __('messages.description_ar') }}</h6>
                            <p>{{ $disease->description_ar ?: '-' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Symptoms Cards --}}
        <div class="row">
            {{-- Clinical Signs --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #0d6efd;">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="ti ti-stethoscope me-2"></i>
                                {{ __('messages.clinical_signs') }}
                            </h6>
                            <span class="badge bg-white text-primary">
                                {{ $disease->symptoms->where('category', 'clinical_signs')->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $clinicalSymptoms = $disease->symptoms->where('category', 'clinical_signs');
                        @endphp
                        @forelse($clinicalSymptoms as $symptom)
                            <div class="d-flex align-items-center mb-2 p-2 rounded" style="background-color: rgba(13, 110, 253, 0.1);">
                                <i class="ti ti-point-filled text-primary me-2"></i>
                                <span>{{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-stethoscope-off fs-1 d-block mb-2"></i>
                                {{ __('messages.no_symptoms_selected') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- PM Lesions --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #ffc107;">
                    <div class="card-header bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="ti ti-microscope me-2"></i>
                                {{ __('messages.pm_lesions') }}
                            </h6>
                            <span class="badge bg-dark text-warning">
                                {{ $disease->symptoms->where('category', 'pm_lesions')->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $pmSymptoms = $disease->symptoms->where('category', 'pm_lesions');
                        @endphp
                        @forelse($pmSymptoms as $symptom)
                            <div class="d-flex align-items-center mb-2 p-2 rounded" style="background-color: rgba(255, 193, 7, 0.1);">
                                <i class="ti ti-point-filled text-warning me-2"></i>
                                <span>{{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-microscope-off fs-1 d-block mb-2"></i>
                                {{ __('messages.no_symptoms_selected') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Lab Findings --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm" style="border-top: 4px solid #0dcaf0;">
                    <div class="card-header bg-info text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="ti ti-flask me-2"></i>
                                {{ __('messages.lab_findings') }}
                            </h6>
                            <span class="badge bg-white text-info">
                                {{ $disease->symptoms->where('category', 'lab_findings')->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $labSymptoms = $disease->symptoms->where('category', 'lab_findings');
                        @endphp
                        @forelse($labSymptoms as $symptom)
                            <div class="d-flex align-items-center mb-2 p-2 rounded" style="background-color: rgba(13, 202, 240, 0.1);">
                                <i class="ti ti-point-filled text-info me-2"></i>
                                <span>{{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-flask-off fs-1 d-block mb-2"></i>
                                {{ __('messages.no_symptoms_selected') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Card --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="ti ti-chart-bar me-2"></i>
                    {{ __('messages.statistics') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3 rounded" style="background-color: rgba(30, 111, 106, 0.1);">
                            <h2 class="mb-0" style="color: #1e6f6a;">{{ $disease->symptoms->count() }}</h2>
                            <small class="text-muted">{{ __('messages.total_symptoms') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded" style="background-color: rgba(13, 110, 253, 0.1);">
                            <h2 class="mb-0 text-primary">{{ $disease->symptoms->where('category', 'clinical_signs')->count() }}</h2>
                            <small class="text-muted">{{ __('messages.clinical_signs') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded" style="background-color: rgba(255, 193, 7, 0.1);">
                            <h2 class="mb-0 text-warning">{{ $disease->symptoms->where('category', 'pm_lesions')->count() }}</h2>
                            <small class="text-muted">{{ __('messages.pm_lesions') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded" style="background-color: rgba(13, 202, 240, 0.1);">
                            <h2 class="mb-0 text-info">{{ $disease->symptoms->where('category', 'lab_findings')->count() }}</h2>
                            <small class="text-muted">{{ __('messages.lab_findings') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
