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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('service_title')->nullable();
            $table->string('profile_overview')->nullable();
            $table->string('phone');
            $table->string('company_address');
            $table->string('password');
            $table->decimal('starting_price', 10, 2)->default(0.00);
            $table->string('profile_image')->nullable();
            $table->json('service_information')->nullable();
            $table->string('service_area_coverage')->nullable();
            $table->string('license_number')->nullable();
            $table->string('insurance_information')->nullable();
            $table->string('years_of_experience')->nullable();
            $table->string('uploaded_document')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
    }
};
