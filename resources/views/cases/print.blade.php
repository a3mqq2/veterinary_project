<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.cases') }} - {{ __('messages.print') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1e6f6a;
        }
        .header h1 {
            color: #1e6f6a;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header .date {
            color: #666;
            font-size: 14px;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item .count {
            font-size: 24px;
            font-weight: bold;
            color: #1e6f6a;
        }
        .summary-item .label {
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px 8px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #1e6f6a;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        tr:hover {
            background: #e9ecef;
        }
        .status {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
        }
        .status-open {
            background: #d1fae5;
            color: #065f46;
        }
        .status-in_progress {
            background: #fef3c7;
            color: #92400e;
        }
        .status-closed {
            background: #e5e7eb;
            color: #374151;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        .no-print {
            position: fixed;
            top: 20px;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 20px;
        }
        .btn-print {
            background: #1e6f6a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-print:hover {
            background: #155a56;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            {{ __('messages.print') }}
        </button>
    </div>

    <div class="header">
        <h1>{{ __('messages.cases') }}</h1>
        <div class="date">{{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="count">{{ $cases->count() }}</div>
            <div class="label">{{ __('messages.total_cases') }}</div>
        </div>
        <div class="summary-item">
            <div class="count">{{ $cases->where('status', 'open')->count() }}</div>
            <div class="label">{{ __('messages.status_open') }}</div>
        </div>
        <div class="summary-item">
            <div class="count">{{ $cases->where('status', 'in_progress')->count() }}</div>
            <div class="label">{{ __('messages.status_in_progress') }}</div>
        </div>
        <div class="summary-item">
            <div class="count">{{ $cases->where('status', 'closed')->count() }}</div>
            <div class="label">{{ __('messages.status_closed') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.case_number') }}</th>
                <th>{{ __('messages.owner_name') }}</th>
                <th>{{ __('messages.owner_phone') }}</th>
                <th>{{ __('messages.farm_location') }}</th>
                <th>{{ __('messages.region') }}</th>
                <th>{{ __('messages.flock_size') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cases as $index => $case)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $case->case_number }}</td>
                    <td>{{ $case->owner_name ?? '-' }}</td>
                    <td>{{ $case->owner_phone ?? '-' }}</td>
                    <td>{{ $case->farm_location ?? '-' }}</td>
                    <td>{{ $case->region ? $case->region->localized_name : '-' }}</td>
                    <td>{{ $case->flock_size ?? '-' }}</td>
                    <td>
                        <span class="status status-{{ $case->status }}">
                            {{ $case->getStatusLabel() }}
                        </span>
                    </td>
                    <td>{{ $case->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 30px;">
                        {{ __('messages.no_cases') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        {{ __('messages.printed_on') }}: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>
