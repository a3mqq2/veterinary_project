<?php

namespace App\Http\Controllers;

use App\Models\AnimalCase;
use App\Models\Symptom;
use Illuminate\Http\Request;

class AnimalCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AnimalCase::with(['symptoms', 'user'])->latest();

        // Regular users can only see their own cases
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('owner_phone', 'like', "%{$search}%")
                  ->orWhere('farm_location', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cases = $query->paginate(15);

        return view('cases.index', compact('cases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $symptoms = Symptom::active()->get()->groupBy('category');

        return view('cases.create', compact('symptoms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Farm Owner Information
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            // Farm Information
            'farm_location' => 'nullable|string|max:255',
            'region_id' => 'nullable|exists:regions,id',
            'farm_type' => 'nullable|in:extensive,semi_intensive,intensive',
            'flock_size' => 'nullable|integer|min:0',
            'other_animals' => 'nullable|string',
            // Animal Case Information
            'age_years' => 'nullable|integer|min:0',
            'age_months' => 'nullable|integer|min:0|max:11',
            'breed' => 'nullable|in:local,imported,mixed',
            'milking_ewes' => 'nullable|integer|min:0',
            'dry_ewes' => 'nullable|integer|min:0',
            // Nutrition Program for Milking Ewes
            'milking_feed_type' => 'nullable|string|max:255',
            'milking_daily_consumption' => 'nullable|string|max:255',
            'milking_feeding_schedule' => 'nullable|string|max:255',
            'milking_mineral_vitamin' => 'nullable|boolean',
            // Nutrition Program for Dry Ewes
            'dry_ewes_nutrition' => 'nullable|string',
            // Lambs
            'lambs_health_problems' => 'nullable|string',
            // Vaccination & Medication
            'vaccination_history' => 'nullable|string',
            'medication_programs' => 'nullable|string',
            // Other
            'notes' => 'nullable|string',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'exists:symptoms,id',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['milking_mineral_vitamin'] = $request->has('milking_mineral_vitamin');
        $validated['region_id'] = $request->region_id ?: null;

        $case = AnimalCase::create($validated);

        // Sync symptoms
        if ($request->filled('symptoms')) {
            $case->symptoms()->sync($request->symptoms);
        }

        return redirect()->route('cases.show', $case)
            ->with('success', __('messages.case_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AnimalCase $case)
    {
        // Regular users can only view their own cases
        if (!auth()->user()->isAdmin() && $case->user_id !== auth()->id()) {
            return redirect()->route('cases.index')
                ->with('error', __('messages.unauthorized'));
        }

        $case->load(['symptoms', 'user']);
        $probableDiseases = $case->getProbableDiseases(5);

        return view('cases.show', compact('case', 'probableDiseases'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnimalCase $case)
    {
        // Regular users can only edit their own cases
        if (!auth()->user()->isAdmin() && $case->user_id !== auth()->id()) {
            return redirect()->route('cases.index')
                ->with('error', __('messages.unauthorized'));
        }

        $symptoms = Symptom::active()->get()->groupBy('category');
        $selectedSymptoms = $case->symptoms->pluck('id')->toArray();

        return view('cases.edit', compact('case', 'symptoms', 'selectedSymptoms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnimalCase $case)
    {
        // Regular users can only update their own cases
        if (!auth()->user()->isAdmin() && $case->user_id !== auth()->id()) {
            return redirect()->route('cases.index')
                ->with('error', __('messages.unauthorized'));
        }

        $validated = $request->validate([
            // Farm Owner Information
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            // Farm Information
            'farm_location' => 'nullable|string|max:255',
            'region_id' => 'nullable|exists:regions,id',
            'farm_type' => 'nullable|in:extensive,semi_intensive,intensive',
            'flock_size' => 'nullable|integer|min:0',
            'other_animals' => 'nullable|string',
            // Animal Case Information
            'age_years' => 'nullable|integer|min:0',
            'age_months' => 'nullable|integer|min:0|max:11',
            'breed' => 'nullable|in:local,imported,mixed',
            'milking_ewes' => 'nullable|integer|min:0',
            'dry_ewes' => 'nullable|integer|min:0',
            // Nutrition Program for Milking Ewes
            'milking_feed_type' => 'nullable|string|max:255',
            'milking_daily_consumption' => 'nullable|string|max:255',
            'milking_feeding_schedule' => 'nullable|string|max:255',
            'milking_mineral_vitamin' => 'nullable|boolean',
            // Nutrition Program for Dry Ewes
            'dry_ewes_nutrition' => 'nullable|string',
            // Lambs
            'lambs_health_problems' => 'nullable|string',
            // Vaccination & Medication
            'vaccination_history' => 'nullable|string',
            'medication_programs' => 'nullable|string',
            // Other
            'notes' => 'nullable|string',
            'status' => 'nullable|in:open,in_progress,closed',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'exists:symptoms,id',
        ]);

        $validated['milking_mineral_vitamin'] = $request->has('milking_mineral_vitamin');
        $validated['region_id'] = $request->region_id ?: null;

        $case->update($validated);

        // Sync symptoms
        $case->symptoms()->sync($request->symptoms ?? []);

        return redirect()->route('cases.show', $case)
            ->with('success', __('messages.case_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnimalCase $case)
    {
        // Regular users can only delete their own cases
        if (!auth()->user()->isAdmin() && $case->user_id !== auth()->id()) {
            return redirect()->route('cases.index')
                ->with('error', __('messages.unauthorized'));
        }

        $case->symptoms()->detach();
        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', __('messages.case_deleted'));
    }

    /**
     * Update case status
     */
    public function updateStatus(Request $request, AnimalCase $case)
    {
        // Regular users can only update status of their own cases
        if (!auth()->user()->isAdmin() && $case->user_id !== auth()->id()) {
            return redirect()->route('cases.index')
                ->with('error', __('messages.unauthorized'));
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $case->update($validated);

        return redirect()->back()
            ->with('success', __('messages.status_updated'));
    }

    /**
     * Export cases to Excel
     */
    public function export(Request $request)
    {
        $query = AnimalCase::with(['symptoms', 'user', 'region'])->latest();

        // Regular users can only export their own cases
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('owner_phone', 'like', "%{$search}%")
                  ->orWhere('farm_location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cases = $query->get();

        // Create CSV response
        $filename = 'cases_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($cases) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                __('messages.case_number'),
                __('messages.owner_name'),
                __('messages.owner_phone'),
                __('messages.farm_location'),
                __('messages.region'),
                __('messages.farm_type'),
                __('messages.flock_size'),
                __('messages.breed'),
                __('messages.age'),
                __('messages.status'),
                __('messages.symptoms'),
                __('messages.created_at'),
            ]);

            foreach ($cases as $case) {
                $symptoms = $case->symptoms->map(function($s) {
                    return app()->getLocale() == 'ar' ? $s->name_ar : $s->name_en;
                })->implode(', ');

                fputcsv($file, [
                    $case->case_number,
                    $case->owner_name ?? '',
                    $case->owner_phone ?? '',
                    $case->farm_location ?? '',
                    $case->region ? $case->region->localized_name : '',
                    $case->getFarmTypeLabel() ?? '',
                    $case->flock_size ?? '',
                    $case->getBreedLabel() ?? '',
                    $case->getFormattedAge() ?? '',
                    $case->getStatusLabel(),
                    $symptoms,
                    $case->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import cases from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $data = [];

            if (in_array($extension, ['csv', 'txt'])) {
                // Parse CSV
                $handle = fopen($file->getRealPath(), 'r');
                $headers = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) >= 4) {
                        $data[] = [
                            'owner_name' => $row[1] ?? null,
                            'owner_phone' => $row[2] ?? null,
                            'farm_location' => $row[3] ?? null,
                            'flock_size' => is_numeric($row[6] ?? '') ? (int)$row[6] : null,
                            'status' => 'open',
                            'user_id' => auth()->id(),
                        ];
                    }
                }
                fclose($handle);
            }

            $imported = 0;
            foreach ($data as $item) {
                if (!empty($item['owner_name']) || !empty($item['farm_location'])) {
                    AnimalCase::create($item);
                    $imported++;
                }
            }

            return redirect()->route('cases.index')
                ->with('success', __('messages.import_success') . ' (' . $imported . ')');
        } catch (\Exception $e) {
            return redirect()->route('cases.index')
                ->with('error', __('messages.import_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $filename = 'cases_template.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                __('messages.case_number'),
                __('messages.owner_name'),
                __('messages.owner_phone'),
                __('messages.farm_location'),
                __('messages.region'),
                __('messages.farm_type'),
                __('messages.flock_size'),
            ]);

            // Sample row
            fputcsv($file, [
                '',
                'اسم المالك',
                '0912345678',
                'موقع المزرعة',
                'طرابلس',
                'extensive',
                '100',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print view for cases list
     */
    public function printList(Request $request)
    {
        $query = AnimalCase::with(['symptoms', 'user', 'region'])->latest();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('owner_phone', 'like', "%{$search}%")
                  ->orWhere('farm_location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cases = $query->get();

        return view('cases.print', compact('cases'));
    }

    /**
     * Print view for single case
     */
    public function printShow(AnimalCase $case)
    {
        if (!auth()->user()->isAdmin() && $case->user_id !== auth()->id()) {
            return redirect()->route('cases.index')
                ->with('error', __('messages.unauthorized'));
        }

        $case->load(['symptoms', 'user', 'region']);
        $probableDiseases = $case->getProbableDiseases(5);

        return view('cases.print-show', compact('case', 'probableDiseases'));
    }
}
