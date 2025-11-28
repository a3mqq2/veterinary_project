@extends('layouts.app')

@section('title', __('messages.edit_disease'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-edit me-2"></i>
                        {{ __('messages.edit_disease') }}
                    </h5>
                    <a href="{{ route('diseases.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i>
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @include('layouts.messages')

                <form action="{{ route('diseases.update', $disease) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Basic Information --}}
                    <div class="card mb-4" style="border-color: #1e6f6a;">
                        <div class="card-header" style="background-color: rgba(30, 111, 106, 0.1);">
                            <h6 class="mb-0" style="color: #1e6f6a;">
                                <i class="ti ti-info-circle me-2"></i>
                                {{ __('messages.basic_info') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="name_en">
                                        {{ __('messages.name_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_en') is-invalid @enderror"
                                           id="name_en"
                                           name="name_en"
                                           value="{{ old('name_en', $disease->name_en) }}"
                                           placeholder="{{ __('messages.enter_name_en') }}"
                                           required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="name_ar">
                                        {{ __('messages.name_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_ar') is-invalid @enderror"
                                           id="name_ar"
                                           name="name_ar"
                                           value="{{ old('name_ar', $disease->name_ar) }}"
                                           placeholder="{{ __('messages.enter_name_ar') }}"
                                           required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="description_en">
                                        {{ __('messages.description_en') }}
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror"
                                              id="description_en"
                                              name="description_en"
                                              rows="3"
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en', $disease->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="description_ar">
                                        {{ __('messages.description_ar') }}
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror"
                                              id="description_ar"
                                              name="description_ar"
                                              rows="3"
                                              placeholder="{{ __('messages.enter_description_ar') }}">{{ old('description_ar', $disease->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.status') }}</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $disease->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('messages.active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Symptoms Selection --}}
                    <div class="row">
                        {{-- Clinical Signs --}}
                        <div class="col-md-4 mb-4">
                            <div class="card h-100" style="border-color: #0d6efd;">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="ti ti-stethoscope me-2"></i>
                                        {{ __('messages.clinical_signs') }}
                                    </h6>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($clinicalSigns as $symptom)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="clinical_signs[]"
                                                   value="{{ $symptom->id }}"
                                                   id="clinical_{{ $symptom->id }}"
                                                   {{ in_array($symptom->id, old('clinical_signs', $selectedSymptoms)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="clinical_{{ $symptom->id }}">
                                                {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center mb-0">
                                            {{ __('messages.no_symptoms_available') }}
                                        </p>
                                    @endforelse
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        {{ __('messages.selected') }}: <span class="clinical-count">0</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- PM Lesions --}}
                        <div class="col-md-4 mb-4">
                            <div class="card h-100" style="border-color: #ffc107;">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="ti ti-microscope me-2"></i>
                                        {{ __('messages.pm_lesions') }}
                                    </h6>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($pmLesions as $symptom)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="pm_lesions[]"
                                                   value="{{ $symptom->id }}"
                                                   id="pm_{{ $symptom->id }}"
                                                   {{ in_array($symptom->id, old('pm_lesions', $selectedSymptoms)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pm_{{ $symptom->id }}">
                                                {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center mb-0">
                                            {{ __('messages.no_symptoms_available') }}
                                        </p>
                                    @endforelse
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        {{ __('messages.selected') }}: <span class="pm-count">0</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Lab Findings --}}
                        <div class="col-md-4 mb-4">
                            <div class="card h-100" style="border-color: #0dcaf0;">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="ti ti-flask me-2"></i>
                                        {{ __('messages.lab_findings') }}
                                    </h6>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($labFindings as $symptom)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="lab_findings[]"
                                                   value="{{ $symptom->id }}"
                                                   id="lab_{{ $symptom->id }}"
                                                   {{ in_array($symptom->id, old('lab_findings', $selectedSymptoms)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="lab_{{ $symptom->id }}">
                                                {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center mb-0">
                                            {{ __('messages.no_symptoms_available') }}
                                        </p>
                                    @endforelse
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">
                                        {{ __('messages.selected') }}: <span class="lab-count">0</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    {{ __('messages.update') }}
                                </button>
                                <a href="{{ route('diseases.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-x me-1"></i>
                                    {{ __('messages.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateCount(selector, countClass) {
            const checked = document.querySelectorAll(selector + ':checked').length;
            document.querySelector('.' + countClass).textContent = checked;
        }

        document.querySelectorAll('input[name="clinical_signs[]"]').forEach(function(cb) {
            cb.addEventListener('change', function() {
                updateCount('input[name="clinical_signs[]"]', 'clinical-count');
            });
        });

        document.querySelectorAll('input[name="pm_lesions[]"]').forEach(function(cb) {
            cb.addEventListener('change', function() {
                updateCount('input[name="pm_lesions[]"]', 'pm-count');
            });
        });

        document.querySelectorAll('input[name="lab_findings[]"]').forEach(function(cb) {
            cb.addEventListener('change', function() {
                updateCount('input[name="lab_findings[]"]', 'lab-count');
            });
        });

        // Initial counts
        updateCount('input[name="clinical_signs[]"]', 'clinical-count');
        updateCount('input[name="pm_lesions[]"]', 'pm-count');
        updateCount('input[name="lab_findings[]"]', 'lab-count');
    });
</script>
@endpush
@endsection
