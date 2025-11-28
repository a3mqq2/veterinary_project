@extends('layouts.app')

@section('title', __('messages.add_disease'))

@push('styles')
<style>
    .symptom-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .symptom-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    .symptom-card .card-header {
        padding: 15px 20px;
        border-bottom: none;
    }
    .symptom-card .card-header h6 {
        font-size: 1rem;
        font-weight: 600;
    }
    .symptom-item {
        padding: 10px 15px;
        margin-bottom: 8px;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .symptom-item:hover {
        background: #e9ecef;
    }
    .symptom-item.selected {
        background: linear-gradient(135deg, rgba(30, 111, 106, 0.1) 0%, rgba(30, 111, 106, 0.05) 100%);
        border-right: 3px solid #1e6f6a;
    }
    .symptom-item .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0;
    }
    .symptom-item .form-check-input:checked {
        background-color: #1e6f6a;
        border-color: #1e6f6a;
    }
    .symptom-item .form-check-label {
        cursor: pointer;
        font-size: 0.9rem;
        padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 8px;
    }
    .symptoms-container {
        max-height: 350px;
        overflow-y: auto;
        padding: 15px;
    }
    .symptoms-container::-webkit-scrollbar {
        width: 6px;
    }
    .symptoms-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .symptoms-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    .symptoms-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        padding: 0 8px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .search-symptoms {
        border: none;
        background: rgba(255,255,255,0.9);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .search-symptoms:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
    }
    .info-card {
        border-radius: 12px;
        border: 2px solid #1e6f6a;
        overflow: hidden;
    }
    .info-card .card-header {
        background: linear-gradient(135deg, rgba(30, 111, 106, 0.1) 0%, rgba(30, 111, 106, 0.05) 100%);
        border-bottom: 1px solid rgba(30, 111, 106, 0.2);
        padding: 15px 20px;
    }
    .select-all-btn {
        padding: 4px 12px;
        font-size: 0.75rem;
        border-radius: 15px;
    }
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    .empty-state i {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #1e6f6a;">
                        <i class="ti ti-virus me-2"></i>
                        {{ __('messages.add_disease') }}
                    </h5>
                    <a href="{{ route('diseases.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i>
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body px-4">
                @include('layouts.messages')

                <form action="{{ route('diseases.store') }}" method="POST">
                    @csrf

                    {{-- Basic Information --}}
                    <div class="card info-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0" style="color: #1e6f6a;">
                                <i class="ti ti-info-circle me-2"></i>
                                {{ __('messages.basic_info') }}
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="name_en">
                                        {{ __('messages.name_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ti ti-language text-muted"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control border-start-0 @error('name_en') is-invalid @enderror"
                                               id="name_en"
                                               name="name_en"
                                               value="{{ old('name_en') }}"
                                               placeholder="{{ __('messages.enter_name_en') }}"
                                               required>
                                    </div>
                                    @error('name_en')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="name_ar">
                                        {{ __('messages.name_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ti ti-language text-muted"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control border-start-0 @error('name_ar') is-invalid @enderror"
                                               id="name_ar"
                                               name="name_ar"
                                               value="{{ old('name_ar') }}"
                                               placeholder="{{ __('messages.enter_name_ar') }}"
                                               required>
                                    </div>
                                    @error('name_ar')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="description_en">
                                        {{ __('messages.description_en') }}
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror"
                                              id="description_en"
                                              name="description_en"
                                              rows="3"
                                              style="resize: none;"
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="description_ar">
                                        {{ __('messages.description_ar') }}
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror"
                                              id="description_ar"
                                              name="description_ar"
                                              rows="3"
                                              style="resize: none;"
                                              placeholder="{{ __('messages.enter_description_ar') }}">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               style="width: 3em; height: 1.5em;"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold ms-2" for="is_active">
                                            {{ __('messages.active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Symptoms Selection Section --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0" style="color: #1e6f6a;">
                                <i class="ti ti-list-check me-2"></i>
                                {{ __('messages.select_symptoms') }}
                            </h6>
                            <div class="ms-auto">
                                <span class="badge bg-light text-dark px-3 py-2">
                                    {{ __('messages.total_selected') }}: <strong class="total-count" style="color: #1e6f6a;">0</strong>
                                </span>
                            </div>
                        </div>

                        <div class="row g-4">
                            {{-- Clinical Signs --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="card symptom-card h-100 border-0" style="border-top: 4px solid #0d6efd !important;">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ti ti-stethoscope me-2"></i>
                                            {{ __('messages.clinical_signs') }}
                                        </h6>
                                        <span class="count-badge bg-white text-primary clinical-count">0</span>
                                    </div>
                                    <div class="card-body p-0">
                                        @if($clinicalSigns->count() > 5)
                                        <div class="p-2 border-bottom">
                                            <input type="text" class="form-control form-control-sm search-input" data-target="clinical" placeholder="{{ __('messages.search') }}...">
                                        </div>
                                        @endif
                                        <div class="symptoms-container" id="clinical-container">
                                            @forelse($clinicalSigns as $symptom)
                                                <div class="symptom-item" data-name="{{ strtolower($symptom->name_en . ' ' . $symptom->name_ar) }}">
                                                    <div class="form-check mb-0 d-flex align-items-center">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               name="clinical_signs[]"
                                                               value="{{ $symptom->id }}"
                                                               id="clinical_{{ $symptom->id }}"
                                                               {{ in_array($symptom->id, old('clinical_signs', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label flex-grow-1" for="clinical_{{ $symptom->id }}">
                                                            {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-state">
                                                    <i class="ti ti-stethoscope-off d-block"></i>
                                                    <p class="text-muted mb-0">{{ __('messages.no_symptoms_available') }}</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    @if($clinicalSigns->count() > 0)
                                    <div class="card-footer bg-light border-0 py-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary select-all-btn w-100" data-target="clinical_signs[]">
                                            <i class="ti ti-checks me-1"></i>
                                            {{ __('messages.select_all') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- PM Lesions --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="card symptom-card h-100 border-0" style="border-top: 4px solid #ffc107 !important;">
                                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ti ti-microscope me-2"></i>
                                            {{ __('messages.pm_lesions') }}
                                        </h6>
                                        <span class="count-badge bg-dark text-warning pm-count">0</span>
                                    </div>
                                    <div class="card-body p-0">
                                        @if($pmLesions->count() > 5)
                                        <div class="p-2 border-bottom">
                                            <input type="text" class="form-control form-control-sm search-input" data-target="pm" placeholder="{{ __('messages.search') }}...">
                                        </div>
                                        @endif
                                        <div class="symptoms-container" id="pm-container">
                                            @forelse($pmLesions as $symptom)
                                                <div class="symptom-item" data-name="{{ strtolower($symptom->name_en . ' ' . $symptom->name_ar) }}">
                                                    <div class="form-check mb-0 d-flex align-items-center">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               name="pm_lesions[]"
                                                               value="{{ $symptom->id }}"
                                                               id="pm_{{ $symptom->id }}"
                                                               {{ in_array($symptom->id, old('pm_lesions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label flex-grow-1" for="pm_{{ $symptom->id }}">
                                                            {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-state">
                                                    <i class="ti ti-microscope-off d-block"></i>
                                                    <p class="text-muted mb-0">{{ __('messages.no_symptoms_available') }}</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    @if($pmLesions->count() > 0)
                                    <div class="card-footer bg-light border-0 py-2">
                                        <button type="button" class="btn btn-sm btn-outline-warning select-all-btn w-100" data-target="pm_lesions[]">
                                            <i class="ti ti-checks me-1"></i>
                                            {{ __('messages.select_all') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Lab Findings --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="card symptom-card h-100 border-0" style="border-top: 4px solid #0dcaf0 !important;">
                                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ti ti-flask me-2"></i>
                                            {{ __('messages.lab_findings') }}
                                        </h6>
                                        <span class="count-badge bg-white text-info lab-count">0</span>
                                    </div>
                                    <div class="card-body p-0">
                                        @if($labFindings->count() > 5)
                                        <div class="p-2 border-bottom">
                                            <input type="text" class="form-control form-control-sm search-input" data-target="lab" placeholder="{{ __('messages.search') }}...">
                                        </div>
                                        @endif
                                        <div class="symptoms-container" id="lab-container">
                                            @forelse($labFindings as $symptom)
                                                <div class="symptom-item" data-name="{{ strtolower($symptom->name_en . ' ' . $symptom->name_ar) }}">
                                                    <div class="form-check mb-0 d-flex align-items-center">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               name="lab_findings[]"
                                                               value="{{ $symptom->id }}"
                                                               id="lab_{{ $symptom->id }}"
                                                               {{ in_array($symptom->id, old('lab_findings', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label flex-grow-1" for="lab_{{ $symptom->id }}">
                                                            {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-state">
                                                    <i class="ti ti-flask-off d-block"></i>
                                                    <p class="text-muted mb-0">{{ __('messages.no_symptoms_available') }}</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    @if($labFindings->count() > 0)
                                    <div class="card-footer bg-light border-0 py-2">
                                        <button type="button" class="btn btn-sm btn-outline-info select-all-btn w-100" data-target="lab_findings[]">
                                            <i class="ti ti-checks me-1"></i>
                                            {{ __('messages.select_all') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="d-flex gap-3 pt-4 border-top">
                        <button type="submit" class="btn btn-lg px-5" style="background-color: #1e6f6a; border-color: #1e6f6a; color: white;">
                            <i class="ti ti-device-floppy me-2"></i>
                            {{ __('messages.save') }}
                        </button>
                        <a href="{{ route('diseases.index') }}" class="btn btn-lg btn-outline-secondary px-4">
                            <i class="ti ti-x me-2"></i>
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update counts
        function updateCounts() {
            const clinicalCount = document.querySelectorAll('input[name="clinical_signs[]"]:checked').length;
            const pmCount = document.querySelectorAll('input[name="pm_lesions[]"]:checked').length;
            const labCount = document.querySelectorAll('input[name="lab_findings[]"]:checked').length;
            const totalCount = clinicalCount + pmCount + labCount;

            document.querySelectorAll('.clinical-count').forEach(el => el.textContent = clinicalCount);
            document.querySelectorAll('.pm-count').forEach(el => el.textContent = pmCount);
            document.querySelectorAll('.lab-count').forEach(el => el.textContent = labCount);
            document.querySelectorAll('.total-count').forEach(el => el.textContent = totalCount);
        }

        // Toggle selected class on symptom items
        function updateSelectedClass() {
            document.querySelectorAll('.symptom-item').forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox.checked) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
            });
        }

        // Add change listeners to all checkboxes
        document.querySelectorAll('.symptom-item input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateCounts();
                updateSelectedClass();
            });
        });

        // Click on symptom item to toggle checkbox
        document.querySelectorAll('.symptom-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });

        // Select All buttons
        document.querySelectorAll('.select-all-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = this.dataset.target;
                const checkboxes = document.querySelectorAll(`input[name="${target}"]`);
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);

                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });

                updateCounts();
                updateSelectedClass();

                // Update button text
                this.innerHTML = allChecked
                    ? '<i class="ti ti-checks me-1"></i> {{ __("messages.select_all") }}'
                    : '<i class="ti ti-x me-1"></i> {{ __("messages.deselect_all") }}';
            });
        });

        // Search functionality
        document.querySelectorAll('.search-input').forEach(input => {
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const target = this.dataset.target;
                const container = document.getElementById(target + '-container');

                container.querySelectorAll('.symptom-item').forEach(item => {
                    const name = item.dataset.name;
                    if (name.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Initial update
        updateCounts();
        updateSelectedClass();
    });
</script>
@endpush
@endsection
