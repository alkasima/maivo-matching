@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Job Postings</h2>
        <a href="{{ route('job.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Post New Job
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($jobs->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <h3 class="text-muted">No jobs posted yet</h3>
            <p class="mb-4">Start by creating your first job posting</p>
            <a href="{{ route('job.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Job Posting
            </a>
        </div>
    </div>
    @else
    <div class="row">
        @foreach($jobs as $job)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $job->job_title }}</h5>
                    <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Posted on {{ $job->created_at->format('M d, Y') }}</small>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        {{ $job->installation_address }}
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <i class="fas fa-charging-station text-primary"></i>
                            {{ $job->charging_station_type }}
                        </div>
                        <div class="col-6">
                            <i class="fas fa-clock text-primary"></i>
                            {{ $job->job_duration_estimate }}
                        </div>
                    </div>
                    @if($job->estimated_budget)
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign text-primary"></i>
                        Budget: ${{ number_format($job->estimated_budget, 2) }}
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('job.show', $job) }}" class="btn btn-outline-primary">
                            View Details
                        </a>
                        <div class="d-flex gap-2">
                            <span class="text-muted">
                                <i class="fas fa-eye"></i> {{ $job->views ?? 0 }} views
                            </span>
                            <span class="text-muted">
                                <i class="fas fa-comment"></i> {{ $job->proposals_count ?? 0 }} proposals
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $jobs->links() }}
    </div>
    @endif
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.8rem;
    padding: 0.4em 0.8em;
}

.fas {
    width: 20px;
}

.card-footer {
    border-top: 1px solid rgba(0,0,0,0.05);
}

.gap-2 {
    gap: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection 