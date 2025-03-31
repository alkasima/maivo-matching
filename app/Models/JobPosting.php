<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'job_title',
        'charging_station_type',
        'job_description',
        'estimated_budget',
        'installation_location_type',
        'station_model',
        'installation_complexity',
        'job_duration_estimate',
        'installation_address',
        'latitude',
        'longitude',
        'preferred_start_date',
        'job_flexibility',
        'license_certifications',
        'experience_level',
        'past_project_references',
        'specific_skills',
        'pricing_preference',
        'payment_terms',
        'owner_name',
        'company_name',
        'contact_email',
        'phone_number',
        'contact_method',
        'supporting_documents',
        'similar_jobs_completed',
        'additional_notes'
    ];

    // Add relationship to User model
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Add casting for JSON fields
    protected $casts = [
        'license_certifications' => 'array',
        'specific_skills' => 'array',
        'supporting_documents' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'estimated_budget' => 'float',
    ];
}
