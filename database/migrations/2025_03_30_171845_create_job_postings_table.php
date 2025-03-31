<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('charging_station_type');
            $table->foreignId('owner_id')->constrained('owners');
            $table->string('installation_location_type');
            $table->string('station_model')->nullable();
            $table->string('installation_complexity');
            $table->string('job_duration_estimate');
            $table->text('job_description')->nullable();
            $table->string('installation_address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->date('preferred_start_date');
            $table->string('job_flexibility');
            $table->json('license_certifications')->nullable();
            $table->string('experience_level');
            $table->text('past_project_references')->nullable();
            $table->json('specific_skills')->nullable();
            $table->decimal('estimated_budget', 10, 2)->nullable();
            $table->string('pricing_preference');
            $table->string('payment_terms');
            $table->string('owner_name');
            $table->string('company_name')->nullable();
            $table->string('contact_email');
            $table->string('phone_number')->nullable();
            $table->string('contact_method');
            $table->json('supporting_documents')->nullable();
            $table->text('similar_jobs_completed')->nullable();
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
