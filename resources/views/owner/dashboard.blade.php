@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- EV Stations Section -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">EV Stations</h5>
                        <a href="#" class="text-primary text-decoration-none">+Add New</a>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="stat-card bg-light">
                                <div class="stat-number blue-number">24</div>
                                <div class="text-muted">Total Stations</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card bg-light">
                                <div class="stat-number green-number">16</div>
                                <div class="text-muted">Active</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card bg-light">
                                <div class="stat-number red-number">04</div>
                                <div class="text-muted">Maintenance</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Jobs Section -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Pending Jobs</h5>

                    <div class="job-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Station Maintenance #2469</h6>
                            <span class="progress-badge">In Progress</span>
                        </div>
                        <div class="text-muted mb-2">Posted 2 days ago</div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-eye me-2 text-muted"></i>
                            <small class="text-muted">Viewed by 8 contractors</small>
                        </div>
                    </div>

                    <hr>

                    <div class="job-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Charging Port Repair #2469</h6>
                            <span class="progress-badge">In Progress</span>
                        </div>
                        <div class="text-muted mb-2">Posted 2 days ago</div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-eye me-2 text-muted"></i>
                            <small class="text-muted">Viewed by 8 contractors</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Contact Section -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Support Contact</h5>
                    <div class="support-box">
                        <div class="text-muted mb-1">Maivo Support Line</div>
                        <div class="support-number mb-1">1-800-532-09</div>
                        <small class="text-muted">Available 24/7 for emergency support</small>
                    </div>
                </div>
            </div>

            <!-- Post New Job Section -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Post New Job</h5>
                    <button class="btn btn-primary w-100 py-2 mb-3">
                        <i class="bi bi-plus-lg me-2"></i>Create Job Request
                    </button>
                    <p class="text-muted small">Quick post a new job request for your EV station.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection