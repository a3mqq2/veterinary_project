<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const CATEGORIES = [
        'clinical_signs' => 'Clinical Signs',
        'pm_lesions' => 'PM Lesions (Post-mortem Lesions)',
        'lab_findings' => 'Lab Findings',
    ];

    public const CATEGORIES_AR = [
        'clinical_signs' => 'العلامات السريرية',
        'pm_lesions' => 'الآفات التشريحية',
        'lab_findings' => 'نتائج المختبر',
    ];

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getCategoryLabelAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return self::CATEGORIES_AR[$this->category] ?? $this->category;
        }
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get all diseases associated with this symptom
     */
    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_symptom')
                    ->withTimestamps();
    }
}
