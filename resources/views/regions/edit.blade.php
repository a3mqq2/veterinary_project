@extends('layouts.app')

@section('title', __('messages.edit_region'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-map-pin-cog me-2"></i>
                    {{ __('messages.edit_region') }}: {{ $region->name }}
                </h5>
                <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i>
                    {{ __('messages.back') }}
                </a>
            </div>
            <div class="card-body">
                @include('layouts.messages')

                <form action="{{ route('regions.update', $region) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.name_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $region->name) }}" placeholder="{{ __('messages.enter_region_name_ar') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.name_en') }}</label>
                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $region->name_en) }}" placeholder="{{ __('messages.enter_region_name_en') }}">
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('messages.description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="{{ __('messages.enter_description') }}">{{ old('description', $region->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.status') }}</label>
                            <div class="form-check mt-2">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $region->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('messages.active') }}</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-x me-1"></i>
                            {{ __('messages.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                            <i class="ti ti-device-floppy me-1"></i>
                            {{ __('messages.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
