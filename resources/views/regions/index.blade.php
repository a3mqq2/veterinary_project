@extends('layouts.app')

@section('title', __('messages.regions'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-map-pin me-2"></i>
                    {{ __('messages.regions') }}
                </h5>
                <a href="{{ route('regions.create') }}" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                    <i class="ti ti-plus me-1"></i>
                    {{ __('messages.add_region') }}
                </a>
            </div>
            <div class="card-body">
                @include('layouts.messages')

                {{-- Filters --}}
                <form method="GET" action="{{ route('regions.index') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary me-2">
                            <i class="ti ti-filter me-1"></i>
                            {{ __('messages.filter') }}
                        </button>
                        <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh me-1"></i>
                            {{ __('messages.reset') }}
                        </a>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.name_ar') }}</th>
                                <th>{{ __('messages.name_en') }}</th>
                                <th>{{ __('messages.cases_count') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.created_at') }}</th>
                                <th class="text-center">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($regions as $region)
                                <tr>
                                    <td>{{ $loop->iteration + ($regions->currentPage() - 1) * $regions->perPage() }}</td>
                                    <td>
                                        <span class="fw-medium">{{ $region->name }}</span>
                                    </td>
                                    <td>{{ $region->name_en ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $region->cases_count }}</span>
                                    </td>
                                    <td>
                                        @if($region->is_active)
                                            <span class="badge bg-success">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $region->created_at->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('regions.edit', $region) }}" class="btn btn-sm btn-outline-primary" title="{{ __('messages.edit') }}">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('regions.toggle-status', $region) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $region->is_active ? 'warning' : 'success' }}" title="{{ $region->is_active ? __('messages.deactivate') : __('messages.activate') }}">
                                                    <i class="ti ti-{{ $region->is_active ? 'toggle-right' : 'toggle-left' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('regions.destroy', $region) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('messages.delete') }}" {{ $region->cases_count > 0 ? 'disabled' : '' }}>
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-map-pin-off fs-1 d-block mb-2"></i>
                                            {{ __('messages.no_regions') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $regions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
