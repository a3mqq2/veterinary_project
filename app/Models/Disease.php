<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all symptoms associated with this disease
     */
    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'disease_symptom')
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
     * Get localized name
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get localized description
     */
    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Scope for active diseases
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
