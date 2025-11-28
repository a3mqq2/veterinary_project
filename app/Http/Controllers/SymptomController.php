<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    public function index(Request $request)
    {
        $query = Symptom::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $symptoms = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('symptoms.index', compact('symptoms'));
    }

    public function create()
    {
        $categories = Symptom::CATEGORIES;
        $categoriesAr = Symptom::CATEGORIES_AR;
        return view('symptoms.create', compact('categories', 'categoriesAr'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'category' => 'required|in:clinical_signs,pm_lesions,lab_findings',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Symptom::create($validated);

        return redirect()->route('symptoms.index')
            ->with('success', __('messages.symptom_created'));
    }

    public function show(Symptom $symptom)
    {
        return view('symptoms.show', compact('symptom'));
    }

    public function edit(Symptom $symptom)
    {
        $categories = Symptom::CATEGORIES;
        $categoriesAr = Symptom::CATEGORIES_AR;
        return view('symptoms.edit', compact('symptom', 'categories', 'categoriesAr'));
    }

    public function update(Request $request, Symptom $symptom)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'category' => 'required|in:clinical_signs,pm_lesions,lab_findings',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $symptom->update($validated);

        return redirect()->route('symptoms.index')
            ->with('success', __('messages.symptom_updated'));
    }

    public function destroy(Symptom $symptom)
    {
        $symptom->delete();

        return redirect()->route('symptoms.index')
            ->with('success', __('messages.symptom_deleted'));
    }

    public function toggleStatus(Symptom $symptom)
    {
        $symptom->update(['is_active' => !$symptom->is_active]);

        return redirect()->route('symptoms.index')
            ->with('success', __('messages.status_updated'));
    }
}
