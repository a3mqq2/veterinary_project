<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Http\Request;

class DiseaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Disease::with(['symptoms']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $diseases = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('diseases.index', compact('diseases'));
    }

    public function create()
    {
        $clinicalSigns = Symptom::active()->byCategory('clinical_signs')->get();
        $pmLesions = Symptom::active()->byCategory('pm_lesions')->get();
        $labFindings = Symptom::active()->byCategory('lab_findings')->get();

        return view('diseases.create', compact('clinicalSigns', 'pmLesions', 'labFindings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
            'clinical_signs' => 'nullable|array',
            'clinical_signs.*' => 'exists:symptoms,id',
            'pm_lesions' => 'nullable|array',
            'pm_lesions.*' => 'exists:symptoms,id',
            'lab_findings' => 'nullable|array',
            'lab_findings.*' => 'exists:symptoms,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $disease = Disease::create([
            'name_en' => $validated['name_en'],
            'name_ar' => $validated['name_ar'],
            'description_en' => $validated['description_en'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        // Attach all symptoms
        $symptoms = array_merge(
            $request->input('clinical_signs', []),
            $request->input('pm_lesions', []),
            $request->input('lab_findings', [])
        );

        $disease->symptoms()->sync($symptoms);

        return redirect()->route('diseases.index')
            ->with('success', __('messages.disease_created'));
    }

    public function show(Disease $disease)
    {
        $disease->load(['symptoms']);
        return view('diseases.show', compact('disease'));
    }

    public function edit(Disease $disease)
    {
        $disease->load(['symptoms']);

        $clinicalSigns = Symptom::active()->byCategory('clinical_signs')->get();
        $pmLesions = Symptom::active()->byCategory('pm_lesions')->get();
        $labFindings = Symptom::active()->byCategory('lab_findings')->get();

        $selectedSymptoms = $disease->symptoms->pluck('id')->toArray();

        return view('diseases.edit', compact('disease', 'clinicalSigns', 'pmLesions', 'labFindings', 'selectedSymptoms'));
    }

    public function update(Request $request, Disease $disease)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
            'clinical_signs' => 'nullable|array',
            'clinical_signs.*' => 'exists:symptoms,id',
            'pm_lesions' => 'nullable|array',
            'pm_lesions.*' => 'exists:symptoms,id',
            'lab_findings' => 'nullable|array',
            'lab_findings.*' => 'exists:symptoms,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $disease->update([
            'name_en' => $validated['name_en'],
            'name_ar' => $validated['name_ar'],
            'description_en' => $validated['description_en'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        // Sync all symptoms
        $symptoms = array_merge(
            $request->input('clinical_signs', []),
            $request->input('pm_lesions', []),
            $request->input('lab_findings', [])
        );

        $disease->symptoms()->sync($symptoms);

        return redirect()->route('diseases.index')
            ->with('success', __('messages.disease_updated'));
    }

    public function destroy(Disease $disease)
    {
        $disease->symptoms()->detach();
        $disease->delete();

        return redirect()->route('diseases.index')
            ->with('success', __('messages.disease_deleted'));
    }

    public function toggleStatus(Disease $disease)
    {
        $disease->update(['is_active' => !$disease->is_active]);

        return redirect()->route('diseases.index')
            ->with('success', __('messages.status_updated'));
    }
}
