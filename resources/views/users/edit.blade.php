@extends('layouts.app')

@section('title', __('messages.edit_user'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-user-edit me-2"></i>
                    {{ __('messages.edit_user') }}: {{ $user->name }}
                </h5>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i>
                    {{ __('messages.back') }}
                </a>
            </div>
            <div class="card-body">
                @include('layouts.messages')

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="{{ __('messages.enter_name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="{{ __('messages.enter_email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.password') }}</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('messages.leave_blank') }}">
                            <small class="text-muted">{{ __('messages.password_hint') }}</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('messages.confirm_password') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.role') }} <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('messages.role_admin') }}</option>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>{{ __('messages.role_user') }}</option>
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <small class="text-muted">{{ __('messages.cannot_change_own_role') }}</small>
                            @endif
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('messages.status') }}</label>
                            <div class="form-check mt-2">
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="is_active" value="1">
                                @else
                                    <input type="hidden" name="is_active" value="0">
                                @endif
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('messages.active') }}</label>
                                @if($user->id === auth()->id())
                                    <br><small class="text-muted">{{ __('messages.cannot_deactivate_self') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
