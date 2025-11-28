@extends('layouts.app')

@section('title', __('messages.edit_symptom'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-edit me-2"></i>
                        {{ __('messages.edit_symptom') }}
                    </h5>
                    <a href="{{ route('symptoms.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i>
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @include('layouts.messages')

                <form action="{{ route('symptoms.update', $symptom) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- English Name --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name_en">
                                {{ __('messages.name_en') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name_en') is-invalid @enderror"
                                   id="name_en"
                                   name="name_en"
                                   value="{{ old('name_en', $symptom->name_en) }}"
                                   placeholder="{{ __('messages.enter_name_en') }}"
                                   required>
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Arabic Name --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name_ar">
                                {{ __('messages.name_ar') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name_ar') is-invalid @enderror"
                                   id="name_ar"
                                   name="name_ar"
                                   value="{{ old('name_ar', $symptom->name_ar) }}"
                                   placeholder="{{ __('messages.enter_name_ar') }}"
                                   required>
                            @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="category">
                                {{ __('messages.category') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('category') is-invalid @enderror"
                                    id="category"
                                    name="category"
                                    required>
                                <option value="">{{ __('messages.select_category') }}</option>
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ old('category', $symptom->category) == $key ? 'selected' : '' }}>
                                        {{ app()->getLocale() == 'ar' ? $categoriesAr[$key] : $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('messages.status') }}</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $symptom->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ __('messages.active') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    {{ __('messages.update') }}
                                </button>
                                <a href="{{ route('symptoms.index') }}" class="btn btn-outline-secondary">
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
@endsection
