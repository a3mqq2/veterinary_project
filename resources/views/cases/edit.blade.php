@extends('layouts.app')

@section('title', __('messages.edit_case'))

@section('content')
<style>
    .section-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .section-header {
        background: linear-gradient(135deg, #1e6f6a 0%, #2a9d8f 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .section-header i {
        margin-inline-end: 0.5rem;
    }
    .section-body {
        padding: 1.5rem;
    }
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #1e6f6a;
        box-shadow: 0 0 0 0.2rem rgba(30, 111, 106, 0.15);
    }

    /* Symptoms Selection Styles */
    .symptoms-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
    }
    .category-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        height: 100%;
    }
    .category-header {
        padding: 1rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .category-header.clinical { background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%); color: white; }
    .category-header.pm { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
    .category-header.lab { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; }
    .category-body {
        padding: 1rem;
        max-height: 300px;
        overflow-y: auto;
    }
    .category-body::-webkit-scrollbar {
        width: 6px;
    }
    .category-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    .category-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    .symptom-item {
        display: flex;
        align-items: center;
        padding: 0.6rem 0.8rem;
        margin-bottom: 0.4rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        background: #f8f9fa;
    }
    .symptom-item:hover {
        background: #e9ecef;
    }
    .symptom-item.selected {
        background: #e8f5f4;
        border-color: #1e6f6a;
    }
    .symptom-item input[type="checkbox"] {
        display: none;
    }
    .symptom-checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid #dee2e6;
        border-radius: 4px;
        margin-inline-end: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .symptom-item.selected .symptom-checkbox {
        background: #1e6f6a;
        border-color: #1e6f6a;
    }
    .symptom-checkbox i {
        color: white;
        font-size: 12px;
        opacity: 0;
    }
    .symptom-item.selected .symptom-checkbox i {
        opacity: 1;
    }
    .symptom-name {
        flex: 1;
        font-size: 0.9rem;
    }
    .count-badge {
        background: rgba(255,255,255,0.3);
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .no-symptoms {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }
</style>

<form action="{{ route('cases.update', $case) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="ti ti-edit me-2" style="color: #1e6f6a;"></i>
                    {{ __('messages.edit_case') }}: {{ $case->case_number }}
                </h4>
                <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-right me-1"></i>
                    {{ __('messages.back') }}
                </a>
            </div>

            @include('layouts.messages')
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-lg-6">
            {{-- Farm Owner Information --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-user"></i>
                    {{ __('messages.farm_owner_info') }}
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.owner_name') }}</label>
                            <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $case->owner_name) }}" placeholder="{{ __('messages.enter_owner_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.owner_phone') }}</label>
                            <input type="text" name="owner_phone" class="form-control" value="{{ old('owner_phone', $case->owner_phone) }}" placeholder="{{ __('messages.enter_phone') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Farm Information --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-building-farm"></i>
                    {{ __('messages.farm_info') }}
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.region') }}</label>
                            <select name="region_id" id="region_id" class="form-control region-select2" style="width: 100%;">
                                <option value="">{{ __('messages.select_or_create_region') }}</option>
                                @if($case->region)
                                    <option value="{{ $case->region->id }}" selected>{{ $case->region->localized_name }}</option>
                                @endif
                            </select>
                            <small class="text-muted">{{ __('messages.region_hint') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.farm_location') }}</label>
                            <input type="text" name="farm_location" class="form-control" value="{{ old('farm_location', $case->farm_location) }}" placeholder="{{ __('messages.enter_location') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.farm_type') }}</label>
                            <select name="farm_type" class="form-select">
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="extensive" {{ old('farm_type', $case->farm_type) == 'extensive' ? 'selected' : '' }}>{{ __('messages.extensive') }}</option>
                                <option value="semi_intensive" {{ old('farm_type', $case->farm_type) == 'semi_intensive' ? 'selected' : '' }}>{{ __('messages.semi_intensive') }}</option>
                                <option value="intensive" {{ old('farm_type', $case->farm_type) == 'intensive' ? 'selected' : '' }}>{{ __('messages.intensive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.flock_size') }}</label>
                            <input type="number" name="flock_size" class="form-control" value="{{ old('flock_size', $case->flock_size) }}" min="0" placeholder="{{ __('messages.enter_flock_size') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.other_animals') }}</label>
                            <input type="text" name="other_animals" class="form-control" value="{{ old('other_animals', $case->other_animals) }}" placeholder="{{ __('messages.enter_other_animals') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Animal Case Information --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-pig"></i>
                    {{ __('messages.animal_info') }}
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.age_years') }}</label>
                            <input type="number" name="age_years" class="form-control" value="{{ old('age_years', $case->age_years) }}" min="0" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.age_months') }}</label>
                            <input type="number" name="age_months" class="form-control" value="{{ old('age_months', $case->age_months) }}" min="0" max="11" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.breed') }}</label>
                            <select name="breed" class="form-select">
                                <option value="">{{ __('messages.select') }}</option>
                                <option value="local" {{ old('breed', $case->breed) == 'local' ? 'selected' : '' }}>{{ __('messages.breed_local') }}</option>
                                <option value="imported" {{ old('breed', $case->breed) == 'imported' ? 'selected' : '' }}>{{ __('messages.breed_imported') }}</option>
                                <option value="mixed" {{ old('breed', $case->breed) == 'mixed' ? 'selected' : '' }}>{{ __('messages.breed_mixed') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.milking_ewes') }}</label>
                            <input type="number" name="milking_ewes" class="form-control" value="{{ old('milking_ewes', $case->milking_ewes) }}" min="0" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.dry_ewes') }}</label>
                            <input type="number" name="dry_ewes" class="form-control" value="{{ old('dry_ewes', $case->dry_ewes) }}" min="0" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-flag"></i>
                    {{ __('messages.case_status') }}
                </div>
                <div class="section-body">
                    <select name="status" class="form-select">
                        <option value="open" {{ old('status', $case->status) == 'open' ? 'selected' : '' }}>{{ __('messages.status_open') }}</option>
                        <option value="in_progress" {{ old('status', $case->status) == 'in_progress' ? 'selected' : '' }}>{{ __('messages.status_in_progress') }}</option>
                        <option value="closed" {{ old('status', $case->status) == 'closed' ? 'selected' : '' }}>{{ __('messages.status_closed') }}</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-6">
            {{-- Nutrition Program for Milking Ewes --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-leaf"></i>
                    {{ __('messages.nutrition_milking') }}
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.feed_type') }}</label>
                            <input type="text" name="milking_feed_type" class="form-control" value="{{ old('milking_feed_type', $case->milking_feed_type) }}" placeholder="{{ __('messages.enter_feed_type') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.daily_consumption') }}</label>
                            <input type="text" name="milking_daily_consumption" class="form-control" value="{{ old('milking_daily_consumption', $case->milking_daily_consumption) }}" placeholder="{{ __('messages.enter_daily_consumption') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.feeding_schedule') }}</label>
                            <input type="text" name="milking_feeding_schedule" class="form-control" value="{{ old('milking_feeding_schedule', $case->milking_feeding_schedule) }}" placeholder="{{ __('messages.enter_feeding_schedule') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.mineral_vitamin') }}</label>
                            <div class="form-check mt-2">
                                <input type="checkbox" name="milking_mineral_vitamin" class="form-check-input" id="milking_mineral_vitamin" {{ old('milking_mineral_vitamin', $case->milking_mineral_vitamin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="milking_mineral_vitamin">{{ __('messages.provided') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nutrition Program for Dry Ewes --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-plant"></i>
                    {{ __('messages.nutrition_dry') }}
                </div>
                <div class="section-body">
                    <textarea name="dry_ewes_nutrition" class="form-control" rows="3" placeholder="{{ __('messages.enter_nutrition_program') }}">{{ old('dry_ewes_nutrition', $case->dry_ewes_nutrition) }}</textarea>
                </div>
            </div>

            {{-- Lambs --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-heart"></i>
                    {{ __('messages.lambs_health') }}
                </div>
                <div class="section-body">
                    <label class="form-label">{{ __('messages.lambs_health_question') }}</label>
                    <textarea name="lambs_health_problems" class="form-control" rows="2" placeholder="{{ __('messages.enter_health_problems') }}">{{ old('lambs_health_problems', $case->lambs_health_problems) }}</textarea>
                </div>
            </div>

            {{-- Vaccination & Medication --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-vaccine"></i>
                    {{ __('messages.vaccination_medication') }}
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.vaccination_history') }}</label>
                            <textarea name="vaccination_history" class="form-control" rows="2" placeholder="{{ __('messages.enter_vaccination_history') }}">{{ old('vaccination_history', $case->vaccination_history) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('messages.medication_programs') }}</label>
                            <textarea name="medication_programs" class="form-control" rows="2" placeholder="{{ __('messages.enter_medication_programs') }}">{{ old('medication_programs', $case->medication_programs) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="section-card">
                <div class="section-header">
                    <i class="ti ti-notes"></i>
                    {{ __('messages.notes') }}
                </div>
                <div class="section-body">
                    <textarea name="notes" class="form-control" rows="2" placeholder="{{ __('messages.enter_notes') }}">{{ old('notes', $case->notes) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Symptoms Selection --}}
    <div class="section-card">
        <div class="section-header">
            <i class="ti ti-stethoscope"></i>
            {{ __('messages.select_symptoms') }}
            <span class="count-badge" id="totalSelectedBadge">0 {{ __('messages.selected') }}</span>
        </div>
        <div class="section-body symptoms-section">
            <div class="row g-4">
                {{-- Clinical Signs --}}
                <div class="col-md-4">
                    <div class="category-card">
                        <div class="category-header clinical">
                            <span><i class="ti ti-stethoscope me-2"></i>{{ __('messages.clinical_signs') }}</span>
                            <span class="count-badge" id="clinicalCount">0</span>
                        </div>
                        <div class="category-body" id="clinicalSymptoms">
                            @if(isset($symptoms['clinical_signs']) && $symptoms['clinical_signs']->count() > 0)
                                @foreach($symptoms['clinical_signs'] as $symptom)
                                    <label class="symptom-item" data-category="clinical">
                                        <input type="checkbox" name="symptoms[]" value="{{ $symptom->id }}" {{ in_array($symptom->id, old('symptoms', $selectedSymptoms)) ? 'checked' : '' }}>
                                        <span class="symptom-checkbox"><i class="ti ti-check"></i></span>
                                        <span class="symptom-name">{{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}</span>
                                    </label>
                                @endforeach
                            @else
                                <div class="no-symptoms">
                                    <i class="ti ti-mood-empty fs-2 d-block mb-2"></i>
                                    {{ __('messages.no_symptoms_available') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PM Lesions --}}
                <div class="col-md-4">
                    <div class="category-card">
                        <div class="category-header pm">
                            <span><i class="ti ti-microscope me-2"></i>{{ __('messages.pm_lesions') }}</span>
                            <span class="count-badge" id="pmCount">0</span>
                        </div>
                        <div class="category-body" id="pmSymptoms">
                            @if(isset($symptoms['pm_lesions']) && $symptoms['pm_lesions']->count() > 0)
                                @foreach($symptoms['pm_lesions'] as $symptom)
                                    <label class="symptom-item" data-category="pm">
                                        <input type="checkbox" name="symptoms[]" value="{{ $symptom->id }}" {{ in_array($symptom->id, old('symptoms', $selectedSymptoms)) ? 'checked' : '' }}>
                                        <span class="symptom-checkbox"><i class="ti ti-check"></i></span>
                                        <span class="symptom-name">{{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}</span>
                                    </label>
                                @endforeach
                            @else
                                <div class="no-symptoms">
                                    <i class="ti ti-mood-empty fs-2 d-block mb-2"></i>
                                    {{ __('messages.no_symptoms_available') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Lab Findings --}}
                <div class="col-md-4">
                    <div class="category-card">
                        <div class="category-header lab">
                            <span><i class="ti ti-flask me-2"></i>{{ __('messages.lab_findings') }}</span>
                            <span class="count-badge" id="labCount">0</span>
                        </div>
                        <div class="category-body" id="labSymptoms">
                            @if(isset($symptoms['lab_findings']) && $symptoms['lab_findings']->count() > 0)
                                @foreach($symptoms['lab_findings'] as $symptom)
                                    <label class="symptom-item" data-category="lab">
                                        <input type="checkbox" name="symptoms[]" value="{{ $symptom->id }}" {{ in_array($symptom->id, old('symptoms', $selectedSymptoms)) ? 'checked' : '' }}>
                                        <span class="symptom-checkbox"><i class="ti ti-check"></i></span>
                                        <span class="symptom-name">{{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}</span>
                                    </label>
                                @endforeach
                            @else
                                <div class="no-symptoms">
                                    <i class="ti ti-mood-empty fs-2 d-block mb-2"></i>
                                    {{ __('messages.no_symptoms_available') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="section-card">
        <div class="section-body">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary">
                    <i class="ti ti-x me-1"></i>
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                    <i class="ti ti-device-floppy me-1"></i>
                    {{ __('messages.update') }}
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const symptomItems = document.querySelectorAll('.symptom-item');

    function updateCounts() {
        let clinical = 0, pm = 0, lab = 0;

        symptomItems.forEach(item => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                item.classList.add('selected');
                const category = item.dataset.category;
                if (category === 'clinical') clinical++;
                else if (category === 'pm') pm++;
                else if (category === 'lab') lab++;
            } else {
                item.classList.remove('selected');
            }
        });

        document.getElementById('clinicalCount').textContent = clinical;
        document.getElementById('pmCount').textContent = pm;
        document.getElementById('labCount').textContent = lab;
        document.getElementById('totalSelectedBadge').textContent = (clinical + pm + lab) + ' {{ __('messages.selected') }}';
    }

    symptomItems.forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        if (checkbox) {
            // Listen to change event on checkbox instead of click on label
            checkbox.addEventListener('change', function() {
                updateCounts();
            });
        }
    });

    // Initial count
    updateCounts();
});
</script>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for regions with AJAX search and tagging (create new)
    $('#region_id').select2({
        placeholder: '{{ __('messages.select_or_create_region') }}',
        allowClear: true,
        tags: true,
        minimumInputLength: 0,
        ajax: {
            url: '{{ route('api.regions.search') }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term || ''
                };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        createTag: function(params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term + ' ({{ __('messages.add') }})',
                newTag: true
            };
        },
        language: {
            noResults: function() {
                return '{{ __('messages.no_results') }}';
            },
            searching: function() {
                return '{{ __('messages.searching') }}';
            }
        },
        dir: '{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}'
    });

    // Handle creating new region when a tag is selected
    $('#region_id').on('select2:select', function(e) {
        var data = e.params.data;
        if (data.newTag) {
            var regionName = data.text.replace(' ({{ __('messages.add') }})', '');
            // Create new region via AJAX
            $.ajax({
                url: '{{ route('api.regions.quick-store') }}',
                type: 'POST',
                data: {
                    name: regionName,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Replace the tag with the actual region
                    var $select = $('#region_id');
                    $select.find('option[value="' + data.id + '"]').remove();
                    var newOption = new Option(response.text, response.id, true, true);
                    $select.append(newOption).trigger('change');
                },
                error: function(xhr) {
                    alert('{{ __('messages.error_creating_region') }}');
                }
            });
        }
    });
});
</script>
@endpush
@endsection
