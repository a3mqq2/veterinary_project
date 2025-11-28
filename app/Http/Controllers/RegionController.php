<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Region::withCount('cases')->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $regions = $query->paginate(15);

        return view('regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('regions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        Region::create($validated);

        return redirect()->route('regions.index')
            ->with('success', __('messages.region_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        $region->loadCount('cases');
        return view('regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        return view('regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

        $region->update($validated);

        return redirect()->route('regions.index')
            ->with('success', __('messages.region_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        // Check if region has cases
        if ($region->cases()->count() > 0) {
            return redirect()->route('regions.index')
                ->with('error', __('messages.region_has_cases'));
        }

        $region->delete();

        return redirect()->route('regions.index')
            ->with('success', __('messages.region_deleted'));
    }

    /**
     * Toggle region active status
     */
    public function toggleStatus(Region $region)
    {
        $region->update(['is_active' => !$region->is_active]);

        return redirect()->back()
            ->with('success', __('messages.status_updated'));
    }

    /**
     * API: Search regions for Select2
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');

        $regions = Region::active()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('name_en', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($region) {
                return [
                    'id' => $region->id,
                    'text' => $region->localized_name,
                ];
            });

        return response()->json(['results' => $regions]);
    }

    /**
     * API: Create new region (for Select2 tags)
     */
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $region = Region::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return response()->json([
            'id' => $region->id,
            'text' => $region->name,
        ]);
    }
}
