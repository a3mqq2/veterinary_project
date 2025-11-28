<?php

namespace App\Http\Controllers;

use App\Models\AnimalCase;
use App\Models\Disease;
use App\Models\Region;
use App\Models\Symptom;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function home()
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        // Base query for cases (filtered by user for non-admins)
        $casesQuery = AnimalCase::query();
        if (!$isAdmin) {
            $casesQuery->where('user_id', $user->id);
        }

        // Cases statistics
        $totalCases = (clone $casesQuery)->count();
        $openCases = (clone $casesQuery)->where('status', 'open')->count();
        $inProgressCases = (clone $casesQuery)->where('status', 'in_progress')->count();
        $closedCases = (clone $casesQuery)->where('status', 'closed')->count();

        // Recent cases
        $recentCases = (clone $casesQuery)
            ->with(['region', 'symptoms'])
            ->latest()
            ->limit(5)
            ->get();

        // Cases by region (top 5)
        $casesByRegion = (clone $casesQuery)
            ->selectRaw('region_id, COUNT(*) as count')
            ->whereNotNull('region_id')
            ->groupBy('region_id')
            ->orderByDesc('count')
            ->limit(5)
            ->with('region')
            ->get();

        // Monthly cases for chart (last 6 months)
        $monthlyCases = (clone $casesQuery)
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Most observed symptoms (top 10)
        $caseIds = (clone $casesQuery)->pluck('id');
        $topSymptoms = Symptom::select([
                'symptoms.id',
                'symptoms.name_en',
                'symptoms.name_ar',
                'symptoms.category',
                'symptoms.is_active'
            ])
            ->selectRaw('COUNT(case_symptom.case_id) as cases_count')
            ->join('case_symptom', 'symptoms.id', '=', 'case_symptom.symptom_id')
            ->whereIn('case_symptom.case_id', $caseIds)
            ->groupBy('symptoms.id', 'symptoms.name_en', 'symptoms.name_ar', 'symptoms.category', 'symptoms.is_active')
            ->orderByDesc('cases_count')
            ->limit(10)
            ->get();

        // Breed distribution
        $breedDistribution = (clone $casesQuery)
            ->selectRaw('breed, COUNT(*) as count')
            ->whereNotNull('breed')
            ->groupBy('breed')
            ->get();

        // Farm type distribution
        $farmTypeDistribution = (clone $casesQuery)
            ->selectRaw('farm_type, COUNT(*) as count')
            ->whereNotNull('farm_type')
            ->groupBy('farm_type')
            ->get();

        // Age distribution (grouped by years)
        $ageDistribution = (clone $casesQuery)
            ->selectRaw('CASE
                WHEN age_years = 0 OR age_years IS NULL THEN "< 1 year"
                WHEN age_years = 1 THEN "1 year"
                WHEN age_years = 2 THEN "2 years"
                WHEN age_years = 3 THEN "3 years"
                WHEN age_years >= 4 THEN "4+ years"
            END as age_group, COUNT(*) as count')
            ->groupBy('age_group')
            ->get();

        // Average flock size
        $avgFlockSize = (clone $casesQuery)->avg('flock_size');

        // Top diseases (most diagnosed based on case symptoms)
        $topDiseases = Disease::select([
                'diseases.id',
                'diseases.name_en',
                'diseases.name_ar',
                'diseases.is_active'
            ])
            ->selectRaw('COUNT(DISTINCT case_symptom.case_id) as cases_count')
            ->join('disease_symptom', 'diseases.id', '=', 'disease_symptom.disease_id')
            ->join('case_symptom', 'disease_symptom.symptom_id', '=', 'case_symptom.symptom_id')
            ->whereIn('case_symptom.case_id', $caseIds)
            ->where('diseases.is_active', true)
            ->groupBy('diseases.id', 'diseases.name_en', 'diseases.name_ar', 'diseases.is_active')
            ->orderByDesc('cases_count')
            ->limit(10)
            ->get();

        // Admin-only statistics
        $adminStats = [];
        if ($isAdmin) {
            $adminStats = [
                'totalUsers' => User::count(),
                'activeUsers' => User::where('is_active', true)->count(),
                'totalDiseases' => Disease::count(),
                'activeDiseases' => Disease::where('is_active', true)->count(),
                'totalSymptoms' => Symptom::count(),
                'activeSymptoms' => Symptom::where('is_active', true)->count(),
                'totalRegions' => Region::count(),
                'activeRegions' => Region::where('is_active', true)->count(),
            ];

            // Top diseases (most matched in cases)
            $adminStats['topDiseases'] = Disease::withCount(['symptoms'])
                ->where('is_active', true)
                ->orderByDesc('symptoms_count')
                ->limit(5)
                ->get();
        }

        return view('home', compact(
            'totalCases',
            'openCases',
            'inProgressCases',
            'closedCases',
            'recentCases',
            'casesByRegion',
            'monthlyCases',
            'topSymptoms',
            'topDiseases',
            'breedDistribution',
            'farmTypeDistribution',
            'ageDistribution',
            'avgFlockSize',
            'adminStats',
            'isAdmin'
        ));
    }
}
