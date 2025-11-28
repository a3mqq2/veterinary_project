@extends('layouts.app')

@section('title', __('messages.cases'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">
                    <i class="ti ti-clipboard-list me-2"></i>
                    {{ __('messages.cases') }}
                </h5>
                <div class="d-flex gap-2 flex-wrap">
                    {{-- Import/Export Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-file-spreadsheet me-1"></i>
                            {{ __('messages.import_export') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('cases.export', request()->query()) }}">
                                    <i class="ti ti-download me-2"></i>
                                    {{ __('messages.export_csv') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('cases.template') }}">
                                    <i class="ti ti-file-download me-2"></i>
                                    {{ __('messages.download_template') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="ti ti-upload me-2"></i>
                                    {{ __('messages.import_csv') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    {{-- Print Button --}}
                    <a href="{{ route('cases.print', request()->query()) }}" class="btn btn-outline-secondary" target="_blank">
                        <i class="ti ti-printer me-1"></i>
                        {{ __('messages.print') }}
                    </a>
                    {{-- Add Case Button --}}
                    <a href="{{ route('cases.create') }}" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                        <i class="ti ti-plus me-1"></i>
                        {{ __('messages.add_case') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @include('layouts.messages')

                {{-- Filters --}}
                <form method="GET" action="{{ route('cases.index') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ __('messages.status_open') }}</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('messages.status_in_progress') }}</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('messages.status_closed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary me-2">
                            <i class="ti ti-filter me-1"></i>
                            {{ __('messages.filter') }}
                        </button>
                        <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary">
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
                                <th>{{ __('messages.case_number') }}</th>
                                <th>{{ __('messages.owner_name') }}</th>
                                <th>{{ __('messages.farm_location') }}</th>
                                <th>{{ __('messages.symptoms') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.created_at') }}</th>
                                <th class="text-center">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cases as $case)
                                <tr>
                                    <td>{{ $loop->iteration + ($cases->currentPage() - 1) * $cases->perPage() }}</td>
                                    <td>
                                        <a href="{{ route('cases.show', $case) }}" class="text-decoration-none" style="color: #1e6f6a; font-weight: 500;">
                                            {{ $case->case_number }}
                                        </a>
                                    </td>
                                    <td>{{ $case->owner_name ?? '-' }}</td>
                                    <td>{{ $case->farm_location ?? '-' }}</td>
                                    <td>
                                        @php
                                            $clinicalCount = $case->symptoms->where('category', 'clinical_signs')->count();
                                            $pmCount = $case->symptoms->where('category', 'pm_lesions')->count();
                                            $labCount = $case->symptoms->where('category', 'lab_findings')->count();
                                        @endphp
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($clinicalCount > 0)
                                                <span class="badge bg-primary" title="{{ __('messages.clinical_signs') }}">
                                                    <i class="ti ti-stethoscope"></i> {{ $clinicalCount }}
                                                </span>
                                            @endif
                                            @if($pmCount > 0)
                                                <span class="badge bg-warning text-dark" title="{{ __('messages.pm_lesions') }}">
                                                    <i class="ti ti-microscope"></i> {{ $pmCount }}
                                                </span>
                                            @endif
                                            @if($labCount > 0)
                                                <span class="badge bg-info" title="{{ __('messages.lab_findings') }}">
                                                    <i class="ti ti-flask"></i> {{ $labCount }}
                                                </span>
                                            @endif
                                            @if($clinicalCount == 0 && $pmCount == 0 && $labCount == 0)
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($case->status == 'open')
                                            <span class="badge bg-success">{{ __('messages.status_open') }}</span>
                                        @elseif($case->status == 'in_progress')
                                            <span class="badge bg-warning text-dark">{{ __('messages.status_in_progress') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('messages.status_closed') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $case->created_at->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('cases.show', $case) }}" class="btn btn-sm btn-outline-info" title="{{ __('messages.view') }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('cases.edit', $case) }}" class="btn btn-sm btn-outline-primary" title="{{ __('messages.edit') }}">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('cases.destroy', $case) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('messages.delete') }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-clipboard-off fs-1 d-block mb-2"></i>
                                            {{ __('messages.no_cases') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $cases->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="ti ti-upload me-2"></i>
                    {{ __('messages.import_csv') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cases.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">{{ __('messages.select_file') }}</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.txt,.xlsx,.xls" required>
                        <div class="form-text">{{ __('messages.supported_formats') }}: CSV, TXT, XLSX, XLS</div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="ti ti-info-circle me-2"></i>
                        {{ __('messages.import_note') }}
                        <a href="{{ route('cases.template') }}" class="alert-link">{{ __('messages.download_template') }}</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #1e6f6a; border-color: #1e6f6a;">
                        <i class="ti ti-upload me-1"></i>
                        {{ __('messages.import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
