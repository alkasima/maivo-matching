@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Marketplace</h2>
    </div>

    <!-- Search and Filters Bar -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="search-container">
                <form action="{{ route('marketplace.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search for contractors..." aria-label="Search">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-md-end gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i> Filters
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?service=Electrical Work">Electrical Work</a></li>
                        <li><a class="dropdown-item" href="?service=Solar Installation">Solar Installation</a></li>
                        <li><a class="dropdown-item" href="?service=Construction">Construction</a></li>
                    </ul>
                </div>
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-map-marker-alt me-2"></i> Locations
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-sort me-2"></i> Sort By
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?sort=created_at&direction=desc">Newest First</a></li>
                        <li><a class="dropdown-item" href="?sort=created_at&direction=asc">Oldest First</a></li>
                        <li><a class="dropdown-item" href="?sort=company_name&direction=asc">Company Name A-Z</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this near the top of the file, perhaps after the search/filter section -->
    <div class="match-container mb-4">
        <button id="ai-match-btn" class="btn btn-primary ai-match-btn">
            <span class="btn-text">
                <i class="fas fa-magic"></i> Find AI Matches for Me
            </span>
            <span class="btn-spinner d-none">
                <i class="fas fa-circle-notch fa-spin"></i> Finding matches...
            </span>
        </button>
        <p class="match-description">Let our AI find the best service providers based on your needs and preferences</p>
        
        <!-- This container will show the top 3 matches -->
        <div id="top-matches-container" class="top-matches-container mt-4 d-none">
            <h4 class="mb-3">Top 3 Matches for You</h4>
            <div id="top-matches" class="row g-4">
                <!-- Matches will be loaded here -->
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('marketplace.match') }}" class="btn btn-outline-primary">
                    See All Matches
                </a>
            </div>
        </div>
    </div>

    <!-- Add this to your HTML -->
    <div class="modal fade" id="needsModal" tabindex="-1" aria-labelledby="needsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="needsModalLabel">Tell us what you need</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="owner-needs">Describe the services you're looking for:</label>
                        <textarea class="form-control" id="owner-needs" rows="3" 
                                  placeholder="Example: I need EV charging installation for a 10-unit apartment building in Seattle"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="find-matches-btn">Find Matches</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contractors Grid -->
    <div class="row g-4">
        @forelse($contractors as $contractor)
            <div class="col-lg-4 col-md-6">
                <div class="card contractor-card h-100">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="contractor-avatar me-3">
                                @if($contractor->profile_image)
                                    <img src="{{ Storage::url($contractor->profile_image) }}" 
                                         alt="{{ $contractor->company_name }}" 
                                         class="rounded-circle">
                                @else
                                    <div class="placeholder-avatar rounded-circle">
                                        {{ strtoupper(substr($contractor->first_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $contractor->first_name }} {{ $contractor->last_name }}</h5>
                                <p class="text-muted mb-1">{{ $contractor->service_title }}</p>
                                <p class="text-muted mb-2">{{ $contractor->company_name }}</p>
                                <div class="rating">
                                    <i class="fas fa-star text-warning"></i>
                                    <span class="fw-bold">4.9</span>
                                    <span class="text-muted">(127)</span>
                                </div>
                            </div>
                        </div>
                        <div class="skills mb-3">
                            @foreach(json_decode($contractor->service_information) ?? [] as $service)
                                <span class="badge bg-primary-subtle text-primary me-2 mb-2">{{ $service }}</span>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="text-muted small">
                                <i class="fas fa-certificate text-primary me-1"></i>
                                {{ $contractor->profile_overview }}
                            </div>
                           
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">Starting from</div>
                            <div class="price">
                                <span class="h4 mb-0 text-primary">${{ number_format($contractor->starting_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No contractors found matching your criteria.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($contractors->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    {{ $contractors->links() }}
                </ul>
            </nav>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const matchButton = document.getElementById('ai-match-btn');
    const needsModal = new bootstrap.Modal(document.getElementById('needsModal'));
    const findMatchesBtn = document.getElementById('find-matches-btn');
    const topMatchesContainer = document.getElementById('top-matches-container');
    const topMatches = document.getElementById('top-matches');
    const contractorsGrid = document.querySelector('.row.g-4'); // The main contractors grid
    
    // Show the modal when clicking the main button
    if (matchButton) {
        matchButton.addEventListener('click', function() {
            needsModal.show();
        });
    }
    
    // Handle the Find Matches button in the modal
    if (findMatchesBtn) {
        findMatchesBtn.addEventListener('click', function() {
            const ownerNeeds = document.getElementById('owner-needs').value;
            
            if (!ownerNeeds) {
                alert('Please describe what services you need');
                return;
            }
            
            // Start animation
            findMatchesBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Finding...';
            findMatchesBtn.disabled = true;
            
            // Call API with the needs
            fetch('{{ route("marketplace.match.ai") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    needs: ownerNeeds
                })
            })
            .then(response => response.json())
            .then(data => {
                // Hide modal
                needsModal.hide();
                
                // Reset button
                findMatchesBtn.innerHTML = 'Find Matches';
                findMatchesBtn.disabled = false;
                
                // Clear previous matches
                topMatches.innerHTML = '';
                
                if (data.matches && data.matches.length > 0) {
                    // Hide the regular contractors grid when we have matches
                    contractorsGrid.style.display = 'none';
                    
                    // Add a "Show All Contractors" button at the top of matches
                    topMatchesContainer.querySelector('.text-center').innerHTML = `
                        <a href="#" id="show-all-contractors" class="btn btn-outline-primary">
                            Show All Contractors
                        </a>
                    `;
                    
                    // Attach event listener to the new button
                    document.getElementById('show-all-contractors').addEventListener('click', function(e) {
                        e.preventDefault();
                        // Show the regular contractors grid
                        contractorsGrid.style.display = 'flex';
                        // Hide the matches container
                        topMatchesContainer.classList.add('d-none');
                    });
                    
                    data.matches.forEach((match, index) => {
                        console.log(`Match ${index}: `, match);
                        console.log(`Contractor ${index} details: `, match.contractor);
                        const contractor = match.contractor;
                        const matchScore = match.match_score || 0;
                        
                        // Skip if contractor data is incomplete
                        if (!contractor || !contractor.first_name) {
                            console.warn("Incomplete contractor data", match);
                            return;
                        }
                        
                        // Restore the nice UI but with safety checks
                        topMatches.innerHTML += `
                            <div class="col-lg-4 col-md-6">
                                <div class="card contractor-card h-100 match-card">
                                    <div class="match-score-badge">${matchScore}% Match</div>
                                    <div class="card-body">
                                        <div class="d-flex mb-3">
                                            <div class="contractor-avatar me-3">
                                                <div class="placeholder-avatar rounded-circle">${contractor.first_name.charAt(0).toUpperCase()}</div>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">${contractor.first_name} ${contractor.last_name || ''}</h5>
                                                <p class="text-muted mb-1">${contractor.service_title || ''}</p>
                                                <p class="text-muted mb-2">${contractor.company_name || ''}</p>
                                            </div>
                                        </div>
                                        <div class="match-reason mb-3">
                                            <strong>Why we matched:</strong> ${match.match_reason || 'Potential match for your needs'}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <a href="#" class="btn btn-sm btn-primary w-100">Contact Provider</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    // Add right after the forEach loop ends
                    console.log("After adding cards, topMatches HTML:", topMatches.innerHTML);
                    console.log("Top matches container:", topMatchesContainer);
                    console.log("topMatchesContainer classes:", topMatchesContainer.className);
                    console.log("topMatchesContainer display style:", window.getComputedStyle(topMatchesContainer).display);
                } else {
                    // Make sure the contractors grid is visible when no matches
                    contractorsGrid.style.display = 'flex';
                    
                    topMatches.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-info">
                                No matches found for your description. Please try with different terms.
                            </div>
                        </div>
                    `;
                }
                
                // Show the matches container
                topMatchesContainer.classList.remove('d-none');
                topMatchesContainer.classList.add('fade-in');

                // Add the following code to force visibility with inline styles:
                topMatchesContainer.style.display = 'block';
                topMatchesContainer.style.visibility = 'visible';
                topMatchesContainer.style.opacity = '1';

                console.log('AI Match Response:', data);
            })
            .catch(error => {
                console.error('Error:', error);
                needsModal.hide();
                findMatchesBtn.innerHTML = 'Find Matches';
                findMatchesBtn.disabled = false;
                
                // Make sure the contractors grid is visible on error
                contractorsGrid.style.display = 'flex';
                
                // Show error message
                topMatches.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            Error finding matches. Please try again later.
                        </div>
                    </div>
                `;
                topMatchesContainer.classList.remove('d-none');
            });
        });
    }
});
</script>

<style>
.container {
    max-width: 1200px;
}

.contractor-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.contractor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.contractor-avatar img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

.placeholder-avatar {
    width: 60px;
    height: 60px;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #6c757d;
}

.badge {
    font-weight: normal;
    padding: 0.5em 1em;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1);
}

.search-container .form-control {
    border-right: none;
    padding-left: 1rem;
}

.search-container .btn {
    border-left: none;
}

.skills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

/* Pagination Styling */
.pagination {
    margin-bottom: 0;
}

.page-link {
    padding: 0.5rem 1rem;
    color: #0d6efd;
    background-color: #fff;
    border: 1px solid #dee2e6;
}

.page-link:hover {
    z-index: 2;
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.justify-content-md-end {
        justify-content: flex-start !important;
        margin-top: 1rem;
    }
    
    .contractor-card {
        margin-bottom: 1rem;
    }
}

/* Button animation styles */
.ai-match-btn {
    position: relative;
    transition: all 0.3s ease;
    overflow: hidden;
}

.ai-match-btn.btn-loading {
    padding-right: 2.5rem;
}

.btn-spinner {
    margin-left: 0.5rem;
}

/* Match card styles */
.match-card {
    position: relative;
    border: 2px solid #0d6efd;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.match-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(13, 110, 253, 0.15);
}

.match-score-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #0d6efd;
    color: white;
    border-radius: 20px;
    padding: 5px 10px;
    font-weight: bold;
    font-size: 0.8rem;
    z-index: 10;
}

.match-reason {
    background-color: rgba(13, 110, 253, 0.05);
    padding: 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.9rem;
}

/* Animation for showing matches */
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.top-matches-container {
    border-top: 1px solid #dee2e6;
    padding-top: 2rem;
    margin-top: 2rem;
}

/* Add this to your existing styles section */
.simple-match-card {
    border: 2px solid blue;
    margin-bottom: 15px;
    padding: 10px;
    background-color: #f0f8ff;
}

.top-matches-container {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>
@endsection 