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
        <div id="top-matches-container" class="top-matches-container d-none">
            <div class="text-center mb-4">
                <h4>AI Matched Contractors</h4>
            </div>
            <div id="top-matches" class="row g-4">
                <!-- AI matches will be inserted here -->
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

    <!-- Replace the pagination section with this Load More button -->
    @if($contractors->hasPages())
        <div class="d-flex justify-content-center mt-4 mb-5">
            <button id="load-more-btn" class="btn btn-primary btn-lg px-4 py-2" 
                    data-current-page="{{ $contractors->currentPage() }}" 
                    data-last-page="{{ $contractors->lastPage() }}">
                <span>Load More Contractors</span>
                <div class="spinner-border spinner-border-sm ms-2 d-none" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </button>
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
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('AI Match Response:', data);
                
                // Hide modal
                needsModal.hide();
                
                // Reset button
                findMatchesBtn.innerHTML = 'Find Matches';
                findMatchesBtn.disabled = false;
                
                // Clear previous matches
                if (topMatches) {
                    topMatches.innerHTML = '';
                } else {
                    console.error("topMatches element not found");
                    return;
                }
                
                if (data.matches && data.matches.length > 0) {
                    // Hide the regular contractors grid when we have matches
                    if (contractorsGrid) {
                        contractorsGrid.style.display = 'none';
                    }
                    
                    // Update the header
                    const headerDiv = topMatchesContainer.querySelector('.text-center');
                    if (headerDiv) {
                        headerDiv.innerHTML = `
                            <h4 class="mb-3">AI Matched Contractors</h4>
                            <p class="text-muted mb-4">Based on your search: "${document.getElementById('owner-needs').value}"</p>
                            <a href="#" id="show-all-contractors" class="btn btn-outline-primary mb-4">
                                Back to All Contractors
                            </a>
                        `;
                    }
                    
                    // Create a row for matches if needed
                    if (!topMatches.classList.contains('row')) {
                        topMatches.className = 'row g-4';
                    }
                    
                    // Attach event listener to the new button
                    const showAllButton = document.getElementById('show-all-contractors');
                    if (showAllButton) {
                        showAllButton.addEventListener('click', function(e) {
                            e.preventDefault();
                            // Show the regular contractors grid
                            if (contractorsGrid) {
                                contractorsGrid.style.display = 'flex';
                            }
                            // Hide the matches container
                            topMatchesContainer.style.display = 'none';
                        });
                    }
                    
                    // Add match cards
                    data.matches.forEach((match, index) => {
                        const contractor = match.contractor;
                        const matchScore = match.match_score || 0;
                        
                        // Skip if contractor data is incomplete
                        if (!contractor || !contractor.first_name) {
                            console.warn("Incomplete contractor data", match);
                            return;
                        }
                        
                        const cardDiv = document.createElement('div');
                        cardDiv.className = 'col-lg-4 col-md-6 mb-4 fade-in-card';
                        cardDiv.innerHTML = `
                            <div class="card contractor-card h-100 match-card">
                                <div class="ai-powered-badge">
                                    <i class="fas fa-robot"></i> AI Powered Match
                                </div>
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
                        `;
                        topMatches.appendChild(cardDiv);
                    });
                    
                    topMatchesContainer.style.display = 'block !important';
                    topMatchesContainer.style.visibility = 'visible';
                    topMatchesContainer.style.opacity = '1';
                    topMatchesContainer.classList.remove('d-none');
                    
                    contractorsGrid.style.display = 'flex';
                
                    void topMatchesContainer.offsetHeight;
                    
                    topMatchesContainer.scrollIntoView({behavior: 'smooth'});
                } else {
                    // Show "no matches" message
                    topMatches.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No matches found for "${document.getElementById('owner-needs').value}" - please try a different search
                            </div>
                        </div>
                    `;
                    
                    // Show the container
                    topMatchesContainer.style.display = 'block';
                    topMatchesContainer.style.visibility = 'visible';
                    topMatchesContainer.style.opacity = '1';
                    topMatchesContainer.classList.remove('d-none');
                    
                    
                    
                }
            })
            .catch(error => {
                console.error('Error with AI matching:', error);
                alert('There was an error finding matches: ' + error.message);
                
                // Hide modal
                needsModal.hide();
                
                // Reset button
                findMatchesBtn.innerHTML = 'Find Matches';
                findMatchesBtn.disabled = false;
                
                if (contractorsGrid) {
                    contractorsGrid.style.display = 'flex';
                }
                
                if (topMatches) {
                    topMatches.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger">
                                Error finding matches. Please try again later.
                            </div>
                        </div>
                    `;
                }
                
                if (topMatchesContainer) {
                    topMatchesContainer.classList.remove('d-none');
                    topMatchesContainer.style.display = 'block';
                    
                }
            });
        });
    }

    // Load more functionality
    const loadMoreBtn = document.getElementById('load-more-btn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const currentPage = parseInt(this.getAttribute('data-current-page'));
            const lastPage = parseInt(this.getAttribute('data-last-page'));
            const nextPage = currentPage + 1;
            
            if (nextPage > lastPage) {
                return;
            }
            
            const spinner = this.querySelector('.spinner-border');
            const buttonText = this.querySelector('span');
            spinner.classList.remove('d-none');
            buttonText.textContent = 'Loading...';
            this.disabled = true;
            
            fetch(`{{ route('marketplace.index') }}?page=${nextPage}&_=${new Date().getTime()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                console.log("Received response for page:", nextPage);
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                
                const newCards = tempDiv.querySelectorAll('.row.g-4 > .col-lg-4');
                console.log("Found new cards:", newCards.length);
                
                
                const contractorsGrid = document.querySelector('.row.g-4');
                
                
                const fragment = document.createDocumentFragment();
                
                
                newCards.forEach(card => {
                    const newCardContainer = document.createElement('div');
                    newCardContainer.className = 'col-lg-4 col-md-6 fade-in-card';
                    newCardContainer.innerHTML = card.innerHTML;
                    fragment.appendChild(newCardContainer);
                });
                
                
                contractorsGrid.appendChild(fragment);
                
                loadMoreBtn.setAttribute('data-current-page', nextPage);
                
                if (nextPage >= lastPage) {
                    loadMoreBtn.classList.add('d-none');
                }
                
                // Reset button state
                spinner.classList.add('d-none');
                buttonText.textContent = 'Load More Contractors';
                loadMoreBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error loading more contractors:', error);
                // Reset button state on error
                spinner.classList.add('d-none');
                buttonText.textContent = 'Try Again';
                loadMoreBtn.disabled = false;
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
    border-left: 4px solid #0d6efd;   
    border-right: 2px solid #dee2e6;  
    border-top: 2px solid #dee2e6;    
    border-bottom: 3px solid #6c757d; 
    border-radius: 6px;               
    transition: all 0.3s ease;        
    box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
}

.contractor-card:hover {
    transform: translateY(-5px);
    border-left: 4px solid #0a58ca;   
    box-shadow: 0 10px 15px rgba(0,0,0,0.1); 
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


.contractor-card.featured {
    border-left: 4px solid #ffc107;   
}

.fade-in-card {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
    transform: translateY(20px);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#load-more-btn {
    transition: all 0.3s ease;
    font-weight: 600;
}

#load-more-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#load-more-btn:active {
    transform: translateY(0);
}

#load-more-btn.disabled {
    cursor: not-allowed;
    opacity: 0.7;
}


.top-matches-container.fade-in {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    height: auto !important;
    overflow: visible !important;
    margin-bottom: 30px;
}

.match-card {
    border: 2px solid #0d6efd;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
}

.match-score-badge {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.ribbon-container {
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    overflow: hidden;
}

.ribbon {
    position: absolute;
    z-index: 1;
    width: 150px;
    padding: 10px 0;
    background-color: #ff6b6b;
    color: white;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    transform: rotate(45deg);
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}

.ribbon-top-right {
    top: 25px;
    right: -40px;
    transform: rotate(45deg);
}

.highlight-box {
    background-color: #fffde7;
    border-left: 4px solid #ffc107;
    padding: 10px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.ai-powered-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg, #4285f4, #34a853);
    color: white;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    z-index: 2;
}

.ai-powered-badge i {
    margin-right: 4px;
}
</style>
@endsection 