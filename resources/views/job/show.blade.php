@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('job.my-jobs') }}">My Jobs</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $job->job_title }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ $job->job_title }}</h3>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Edit Job</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-pause"></i> Pause Listing</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash"></i> Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Job Overview -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Job Overview</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Posted Date</small>
                                        {{ $job->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Duration</small>
                                        {{ $job->job_duration_estimate }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-charging-station text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Station Type</small>
                                        {{ $job->charging_station_type }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Location Type</small>
                                        {{ $job->installation_location_type }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Job Description</h5>
                        <p class="text-justify">{{ $job->job_description }}</p>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Requirements</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-certificate text-primary"></i> Required Certifications</h6>
                                @if($job->license_certifications && is_array($job->license_certifications))
                                    <ul class="list-unstyled">
                                        @foreach($job->license_certifications as $cert)
                                            <li class="mb-1"><i class="fas fa-check text-success me-2"></i>{{ $cert }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No specific certifications required</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-tools text-primary"></i> Required Skills</h6>
                                @if($job->specific_skills && is_array($job->specific_skills))
                                    <ul class="list-unstyled">
                                        @foreach($job->specific_skills as $skill)
                                            <li class="mb-1"><i class="fas fa-check text-success me-2"></i>{{ $skill }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No specific skills listed</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    @if($job->supporting_documents && is_array($job->supporting_documents))
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Supporting Documents</h5>
                        <div class="row g-2">
                            @foreach($job->supporting_documents as $document)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            <a href="{{ Storage::url($document) }}" target="_blank" class="text-decoration-none">
                                                {{ basename($document) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- Budget Info -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Budget Information</h5>
                            @if($job->estimated_budget)
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-dollar-sign text-success me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Estimated Budget</small>
                                    <strong>${{ number_format($job->estimated_budget, 2) }}</strong>
                                </div>
                            </div>
                            @endif
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Payment Terms</small>
                                    {{ $job->payment_terms }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-handshake text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Pricing Type</small>
                                    {{ $job->pricing_preference }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Location</h5>
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                {{ $job->installation_address }}
                            </div>
                            <div id="map" style="height: 200px;" class="rounded"></div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Job Statistics</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <i class="fas fa-eye text-primary me-2"></i>
                                    Views
                                </div>
                                <strong>{{ $job->views ?? 0 }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <i class="fas fa-file-signature text-primary me-2"></i>
                                    Proposals
                                </div>
                                <strong>{{ $job->proposals_count ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.badge {
    font-size: 0.9rem;
    padding: 0.5em 1em;
}

.gap-3 {
    gap: 1rem;
}

.text-justify {
    text-align: justify;
}

.dropdown-item i {
    width: 20px;
}

#map {
    border: 1px solid #dee2e6;
}
</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([{{ $job->latitude }}, {{ $job->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add marker
    const marker = L.marker([{{ $job->latitude }}, {{ $job->longitude }}]).addTo(map);
});
</script>
@endsection 