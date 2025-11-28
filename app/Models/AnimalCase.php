<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalCase extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        // Farm Owner Information
        'owner_name',
        'owner_phone',
        // Farm Information
        'farm_location',
        'region_id',
        'farm_type',
        'flock_size',
        'other_animals',
        // Animal Case Information
        'age_years',
        'age_months',
        'breed',
        'milking_ewes',
        'dry_ewes',
        // Nutrition Program for Milking Ewes
        'milking_feed_type',
        'milking_daily_consumption',
        'milking_feeding_schedule',
        'milking_mineral_vitamin',
        // Nutrition Program for Dry Ewes
        'dry_ewes_nutrition',
        // Lambs
        'lambs_health_problems',
        // Vaccination & Medication
        'vaccination_history',
        'medication_programs',
        // Other
        'notes',
        'status',
        'user_id',
    ];

    protected $casts = [
        'milking_mineral_vitamin' => 'boolean',
        'flock_size' => 'integer',
        'age_years' => 'integer',
        'age_months' => 'integer',
        'milking_ewes' => 'integer',
        'dry_ewes' => 'integer',
    ];

    /**
     * Boot function to generate case number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($case) {
            if (empty($case->case_number)) {
                $case->case_number = 'CASE-' . date('Y') . '-' . str_pad(static::count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get all symptoms associated with this case
     */
    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'case_symptom', 'case_id', 'symptom_id')
                    ->withTimestamps();
    }

    /**
     * Get clinical signs symptoms
     */
    public function clinicalSigns()
    {
        return $this->symptoms()->where('category', 'clinical_signs');
    }

    /**
     * Get PM lesions symptoms
     */
    public function pmLesions()
    {
        return $this->symptoms()->where('category', 'pm_lesions');
    }

    /**
     * Get lab findings symptoms
     */
    public function labFindings()
    {
        return $this->symptoms()->where('category', 'lab_findings');
    }

    /**
     * Get the user who created this case
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the region for this case
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Calculate probable diseases based on symptoms
     * Returns top 5 diseases with matching percentage
     */
    public function getProbableDiseases($limit = 5)
    {
        $caseSymptomIds = $this->symptoms->pluck('id')->toArray();

        if (empty($caseSymptomIds)) {
            return collect();
        }

        $diseases = Disease::active()
            ->with('symptoms')
            ->get()
            ->map(function ($disease) use ($caseSymptomIds) {
                $diseaseSymptomIds = $disease->symptoms->pluck('id')->toArray();

                if (empty($diseaseSymptomIds)) {
                    return null;
                }

                // Calculate matching symptoms
                $matchingSymptoms = array_intersect($caseSymptomIds, $diseaseSymptomIds);
                $matchCount = count($matchingSymptoms);

                // Calculate percentage based on disease symptoms
                $percentage = ($matchCount / count($diseaseSymptomIds)) * 100;

                // Only include if there's at least one match
                if ($matchCount > 0) {
                    return [
                        'disease' => $disease,
                        'match_count' => $matchCount,
                        'total_disease_symptoms' => count($diseaseSymptomIds),
                        'percentage' => round($percentage, 1),
                        'matching_symptom_ids' => $matchingSymptoms,
                    ];
                }

                return null;
            })
            ->filter()
            ->sortByDesc('percentage')
            ->take($limit)
            ->values();

        return $diseases;
    }

    /**
     * Scope for status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get farm type label
     */
    public function getFarmTypeLabel(): ?string
    {
        $types = [
            'extensive' => app()->getLocale() === 'ar' ? 'مفتوح' : 'Extensive',
            'semi_intensive' => app()->getLocale() === 'ar' ? 'شبه مكثف' : 'Semi-intensive',
            'intensive' => app()->getLocale() === 'ar' ? 'مكثف' : 'Intensive',
        ];

        return $this->farm_type ? $types[$this->farm_type] ?? $this->farm_type : null;
    }

    /**
     * Get breed label
     */
    public function getBreedLabel(): ?string
    {
        $breeds = [
            'local' => app()->getLocale() === 'ar' ? 'محلي' : 'Local',
            'imported' => app()->getLocale() === 'ar' ? 'مستورد' : 'Imported',
            'mixed' => app()->getLocale() === 'ar' ? 'هجين' : 'Mixed',
        ];

        return $this->breed ? $breeds[$this->breed] ?? $this->breed : null;
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        $statuses = [
            'open' => app()->getLocale() === 'ar' ? 'مفتوحة' : 'Open',
            'in_progress' => app()->getLocale() === 'ar' ? 'قيد المعالجة' : 'In Progress',
            'closed' => app()->getLocale() === 'ar' ? 'مغلقة' : 'Closed',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get age formatted
     */
    public function getFormattedAge(): ?string
    {
        if (!$this->age_years && !$this->age_months) {
            return null;
        }

        $parts = [];
        if ($this->age_years) {
            $parts[] = $this->age_years . ' ' . (app()->getLocale() === 'ar' ? 'سنة' : 'years');
        }
        if ($this->age_months) {
            $parts[] = $this->age_months . ' ' . (app()->getLocale() === 'ar' ? 'شهر' : 'months');
        }

        return implode(' ', $parts);
    }
}
