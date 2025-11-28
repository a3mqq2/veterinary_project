<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $case->case_number }} - {{ __('messages.print') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: white;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1e6f6a 0%, #2a9d8f 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header .meta {
            font-size: 13px;
            opacity: 0.9;
        }
        .status {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-open {
            background: #10b981;
        }
        .status-in_progress {
            background: #f59e0b;
        }
        .status-closed {
            background: #6b7280;
        }
        .section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .section-header {
            background: #f3f4f6;
            padding: 12px 15px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
            color: #1e6f6a;
        }
        .section-body {
            padding: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 13px;
            color: #1f2937;
            font-weight: 500;
        }
        .diseases-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .diseases-title {
            font-size: 16px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 12px;
        }
        .disease-item {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .disease-item:last-child {
            margin-bottom: 0;
        }
        .disease-rank {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
            margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 12px;
        }
        .rank-1 { background: #fbbf24; }
        .rank-2 { background: #9ca3af; }
        .rank-3 { background: #d97706; }
        .rank-other { background: #e5e7eb; color: #374151; }
        .disease-info {
            flex: 1;
        }
        .disease-name {
            font-weight: 600;
            font-size: 13px;
        }
        .disease-meta {
            font-size: 11px;
            color: #6b7280;
        }
        .disease-percentage {
            font-size: 18px;
            font-weight: 700;
        }
        .percentage-high { color: #059669; }
        .percentage-medium { color: #d97706; }
        .percentage-low { color: #dc2626; }
        .symptoms-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .symptom-tag {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 11px;
        }
        .symptom-tag.clinical { background: #eff6ff; border-color: #93c5fd; }
        .symptom-tag.pm { background: #fefce8; border-color: #fde047; }
        .symptom-tag.lab { background: #ecfeff; border-color: #67e8f9; }
        .symptom-category {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            margin-top: 12px;
        }
        .symptom-category:first-child {
            margin-top: 0;
        }
        .two-columns {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .footer {
            margin-top: 25px;
            padding-top: 15px;
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
            .header, .diseases-section {
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
        <div>
            <h1>{{ $case->case_number }}</h1>
            <div class="meta">
                {{ $case->created_at->format('Y-m-d H:i') }}
                @if($case->user)
                    | {{ $case->user->name }}
                @endif
            </div>
        </div>
        <span class="status status-{{ $case->status }}">
            {{ $case->getStatusLabel() }}
        </span>
    </div>

    {{-- Probable Diseases --}}
    @if($probableDiseases->count() > 0)
    <div class="diseases-section">
        <div class="diseases-title">{{ __('messages.probable_diseases') }}</div>
        @foreach($probableDiseases as $index => $item)
            @php
                $percentage = $item['percentage'];
                $percentageClass = $percentage >= 70 ? 'high' : ($percentage >= 40 ? 'medium' : 'low');
                $rankClass = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other'));
            @endphp
            <div class="disease-item">
                <div class="disease-rank {{ $rankClass }}">{{ $index + 1 }}</div>
                <div class="disease-info">
                    <div class="disease-name">
                        {{ app()->getLocale() == 'ar' ? $item['disease']->name_ar : $item['disease']->name_en }}
                    </div>
                    <div class="disease-meta">
                        {{ __('messages.matching_symptoms') }}: {{ $item['match_count'] }} / {{ $item['total_disease_symptoms'] }}
                    </div>
                </div>
                <div class="disease-percentage percentage-{{ $percentageClass }}">
                    {{ $percentage }}%
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <div class="two-columns">
        {{-- Left Column --}}
        <div>
            {{-- Farm Owner Information --}}
            <div class="section">
                <div class="section-header">{{ __('messages.farm_owner_info') }}</div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.owner_name') }}</span>
                            <span class="info-value">{{ $case->owner_name ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.owner_phone') }}</span>
                            <span class="info-value">{{ $case->owner_phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Farm Information --}}
            <div class="section">
                <div class="section-header">{{ __('messages.farm_info') }}</div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.farm_location') }}</span>
                            <span class="info-value">{{ $case->farm_location ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.region') }}</span>
                            <span class="info-value">{{ $case->region ? $case->region->localized_name : '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.farm_type') }}</span>
                            <span class="info-value">{{ $case->getFarmTypeLabel() ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.flock_size') }}</span>
                            <span class="info-value">{{ $case->flock_size ?? '-' }}</span>
                        </div>
                        <div class="info-item" style="grid-column: span 2;">
                            <span class="info-label">{{ __('messages.other_animals') }}</span>
                            <span class="info-value">{{ $case->other_animals ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Animal Information --}}
            <div class="section">
                <div class="section-header">{{ __('messages.animal_info') }}</div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.age') }}</span>
                            <span class="info-value">{{ $case->getFormattedAge() ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.breed') }}</span>
                            <span class="info-value">{{ $case->getBreedLabel() ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.milking_ewes') }}</span>
                            <span class="info-value">{{ $case->milking_ewes ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.dry_ewes') }}</span>
                            <span class="info-value">{{ $case->dry_ewes ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            {{-- Nutrition Program for Milking Ewes --}}
            <div class="section">
                <div class="section-header">{{ __('messages.nutrition_milking') }}</div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.feed_type') }}</span>
                            <span class="info-value">{{ $case->milking_feed_type ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.daily_consumption') }}</span>
                            <span class="info-value">{{ $case->milking_daily_consumption ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.feeding_schedule') }}</span>
                            <span class="info-value">{{ $case->milking_feeding_schedule ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">{{ __('messages.mineral_vitamin') }}</span>
                            <span class="info-value">{{ $case->milking_mineral_vitamin ? __('messages.provided') : __('messages.not_provided') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vaccination & Medication --}}
            <div class="section">
                <div class="section-header">{{ __('messages.vaccination_medication') }}</div>
                <div class="section-body">
                    <div class="info-item" style="margin-bottom: 10px;">
                        <span class="info-label">{{ __('messages.vaccination_history') }}</span>
                        <span class="info-value">{{ $case->vaccination_history ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">{{ __('messages.medication_programs') }}</span>
                        <span class="info-value">{{ $case->medication_programs ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($case->notes)
            <div class="section">
                <div class="section-header">{{ __('messages.notes') }}</div>
                <div class="section-body">
                    <p>{{ $case->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Selected Symptoms --}}
    <div class="section">
        <div class="section-header">{{ __('messages.selected_symptoms') }} ({{ $case->symptoms->count() }})</div>
        <div class="section-body">
            @if($case->symptoms->count() > 0)
                @php
                    $clinicalSymptoms = $case->symptoms->where('category', 'clinical_signs');
                    $pmSymptoms = $case->symptoms->where('category', 'pm_lesions');
                    $labSymptoms = $case->symptoms->where('category', 'lab_findings');
                @endphp

                @if($clinicalSymptoms->count() > 0)
                    <div class="symptom-category">{{ __('messages.clinical_signs') }}</div>
                    <div class="symptoms-grid">
                        @foreach($clinicalSymptoms as $symptom)
                            <span class="symptom-tag clinical">
                                {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                            </span>
                        @endforeach
                    </div>
                @endif

                @if($pmSymptoms->count() > 0)
                    <div class="symptom-category">{{ __('messages.pm_lesions') }}</div>
                    <div class="symptoms-grid">
                        @foreach($pmSymptoms as $symptom)
                            <span class="symptom-tag pm">
                                {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                            </span>
                        @endforeach
                    </div>
                @endif

                @if($labSymptoms->count() > 0)
                    <div class="symptom-category">{{ __('messages.lab_findings') }}</div>
                    <div class="symptoms-grid">
                        @foreach($labSymptoms as $symptom)
                            <span class="symptom-tag lab">
                                {{ app()->getLocale() == 'ar' ? $symptom->name_ar : $symptom->name_en }}
                            </span>
                        @endforeach
                    </div>
                @endif
            @else
                <p style="text-align: center; color: #6b7280;">{{ __('messages.no_symptoms_selected') }}</p>
            @endif
        </div>
    </div>

    <div class="footer">
        {{ __('messages.printed_on') }}: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>
