@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
<div class="container">
    
    <!-- Add Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Add Error Messages -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please check the form below for errors:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('job.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="container mt-4">
            <h2>Create New Job Posting</h2>

            <!-- Basic Job Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Basic Job Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Job Title*</label>
                            <input type="text" name="job_title" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title') }}" required>
                            @error('job_title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Charging Station Type*</label>
                            <select name="charging_station_type" class="form-control @error('charging_station_type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="Level 1" {{ old('charging_station_type') == 'Level 1' ? 'selected' : '' }}>Level 1 (120V)</option>
                                <option value="Level 2" {{ old('charging_station_type') == 'Level 2' ? 'selected' : '' }}>Level 2 (240V)</option>
                                <option value="DC Fast Charging" {{ old('charging_station_type') == 'DC Fast Charging' ? 'selected' : '' }}>DC Fast Charging</option>
                                <option value="Tesla Specific" {{ old('charging_station_type') == 'Tesla Specific' ? 'selected' : '' }}>Tesla Specific</option>
                            </select>
                            @error('charging_station_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Installation Location Type*</label>
                            <select name="installation_location_type" class="form-control" required>
                                <option value="">Select Location Type</option>
                                <option value="Residential">Residential</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Public">Public</option>
                                <option value="Industrial">Industrial</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Station Model</label>
                            <input type="text" name="station_model" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Installation Complexity*</label>
                            <select name="installation_complexity" class="form-control" required>
                                <option value="">Select Complexity</option>
                                <option value="Simple">Simple</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Complex">Complex</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Job Duration Estimate*</label>
                            <select name="job_duration_estimate" class="form-control" required>
                                <option value="">Select Duration</option>
                                <option value="1-2 days">1-2 days</option>
                                <option value="3-5 days">3-5 days</option>
                                <option value="1-2 weeks">1-2 weeks</option>
                                <option value="2+ weeks">2+ weeks</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Job Description</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Job Description</label>
                        <textarea id="job_description" name="job_description" class="form-control" rows="4"></textarea>
                        <button type="button" onclick="getJobDescription()" class="btn btn-info mt-2">
                            <span id="generateBtnText">Generate with AI</span>
                            <div id="loadingSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Location Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Location Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3 position-relative">
                            <label>Installation Address*</label>
                            <div class="input-group">
                                <input type="text" id="installation_address" name="installation_address" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" id="searchAddress">
                                    <span id="searchButtonText">Search</span>
                                    <div id="searchSpinner" class="spinner-border spinner-border-sm ms-1 d-none" role="status">
                                        <span class="visually-hidden">Searching...</span>
                                    </div>
                                </button>
                            </div>
                            <div id="address-suggestions" class="list-group mt-2 position-absolute w-100" style="display: none; z-index: 1000;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Latitude*</label>
                            <input type="number" id="latitude" name="latitude" class="form-control" step="0.0000001" required >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Longitude*</label>
                            <input type="number" id="longitude" name="longitude" class="form-control" step="0.0000001" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="map" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule & Requirements -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Schedule & Requirements</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Preferred Start Date*</label>
                            <input type="date" name="preferred_start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Job Flexibility*</label>
                            <select name="job_flexibility" class="form-control" required>
                                <option value="">Select Flexibility</option>
                                <option value="Flexible">Flexible</option>
                                <option value="Somewhat Flexible">Somewhat Flexible</option>
                                <option value="Fixed">Fixed</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Required License/Certifications</label>
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="certification-list">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="license_certifications[]" value="Electrical License" id="cert_electrical">
                                            <label class="form-check-label" for="cert_electrical">
                                                Electrical License
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="license_certifications[]" value="EV Certification" id="cert_ev">
                                            <label class="form-check-label" for="cert_ev">
                                                EV Certification
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="license_certifications[]" value="Safety Certification" id="cert_safety">
                                            <label class="form-check-label" for="cert_safety">
                                                Safety Certification
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="license_certifications[]" value="Master Electrician" id="cert_master">
                                            <label class="form-check-label" for="cert_master">
                                                Master Electrician
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="license_certifications[]" value="Journeyman Electrician" id="cert_journeyman">
                                            <label class="form-check-label" for="cert_journeyman">
                                                Journeyman Electrician
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="license_certifications[]" value="EV Charging Installation" id="cert_charging">
                                            <label class="form-check-label" for="cert_charging">
                                                EV Charging Installation
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="license_certifications[]" value="OSHA Safety" id="cert_osha">
                                            <label class="form-check-label" for="cert_osha">
                                                OSHA Safety
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Experience Level*</label>
                            <select name="experience_level" class="form-control" required>
                                <option value="">Select Experience Level</option>
                                <option value="Entry Level">Entry Level</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Expert">Expert</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Past Project References</label>
                        <textarea name="past_project_references" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Specific Skills Required</label>
                        <div class="card">
                            <div class="card-body p-2">
                                <div class="skills-list">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Electrical Wiring" id="skill_wiring">
                                                <label class="form-check-label" for="skill_wiring">
                                                    Electrical Wiring
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Panel Installation" id="skill_panel">
                                                <label class="form-check-label" for="skill_panel">
                                                    Panel Installation
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Troubleshooting" id="skill_troubleshooting">
                                                <label class="form-check-label" for="skill_troubleshooting">
                                                    Troubleshooting
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Network Configuration" id="skill_network">
                                                <label class="form-check-label" for="skill_network">
                                                    Network Configuration
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Circuit Design" id="skill_circuit">
                                                <label class="form-check-label" for="skill_circuit">
                                                    Circuit Design
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Load Calculation" id="skill_load">
                                                <label class="form-check-label" for="skill_load">
                                                    Load Calculation
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Conduit Installation" id="skill_conduit">
                                                <label class="form-check-label" for="skill_conduit">
                                                    Conduit Installation
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specific_skills[]" value="Code Compliance" id="skill_code">
                                                <label class="form-check-label" for="skill_code">
                                                    Code Compliance
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget & Payment -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Budget & Payment</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Estimated Budget</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" id="estimated_budget" name="estimated_budget" class="form-control" readonly>
                                <button type="button" onclick="getBudgetEstimate()" class="btn btn-info">
                                    <span id="budgetBtnText">Get Budget Estimate</span>
                                    <div id="budgetLoadingSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Pricing Preference*</label>
                            <select name="pricing_preference" class="form-control" required>
                                <option value="">Select Preference</option>
                                <option value="Fixed Price">Fixed Price</option>
                                <option value="Hourly Rate">Hourly Rate</option>
                                <option value="Negotiable">Negotiable</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Payment Terms*</label>
                        <select name="payment_terms" class="form-control" required>
                            <option value="">Select Payment Terms</option>
                            <option value="Upon Completion">Upon Completion</option>
                            <option value="50/50 Split">50/50 Split</option>
                            <option value="Milestone Based">Milestone Based</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Owner Name*</label>
                            <input type="text" name="owner_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Company Name</label>
                            <input type="text" name="company_name" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Contact Email*</label>
                            <input type="email" name="contact_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Preferred Contact Method*</label>
                        <select name="contact_method" class="form-control" required>
                            <option value="">Select Contact Method</option>
                            <option value="Email">Email</option>
                            <option value="Phone">Phone</option>
                            <option value="Both">Both</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Supporting Documents</label>
                        <input type="file" name="supporting_documents[]" class="form-control" multiple>
                    </div>

                    <div class="mb-3">
                        <label>Similar Jobs Completed</label>
                        <textarea name="similar_jobs_completed" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Additional Notes</label>
                        <textarea name="additional_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Add these hidden fields to store coordinates -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <div class="text-end mb-4">
                <button type="submit" class="btn btn-primary">Create Job Posting</button>
            </div>
        </div>
    </form>
</div>
</div>

<!-- Suggestions Modal -->
<div class="modal fade" id="suggestionsModal" tabindex="-1" aria-labelledby="suggestionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suggestionsModalLabel">AI Generated Suggestions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="suggestionsList"></div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css"/>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_YOUR_REAL_KEY_HERE&libraries=places"></script>
<script>
function getJobDescription() {
    const spinner = document.getElementById('loadingSpinner');
    const btnText = document.getElementById('generateBtnText');
    
    spinner.classList.remove('d-none');
    btnText.textContent = 'Generating...';

    const jobTitle = document.querySelector('input[name="job_title"]').value;
    const chargingStationType = document.querySelector('select[name="charging_station_type"]').value;

    // Validate input
    if (!jobTitle || !chargingStationType) {
        alert('Please fill in both Job Title and Charging Station Type');
        spinner.classList.add('d-none');
        btnText.textContent = 'Generate with AI';
        return;
    }

    fetch("{{ route('job.ai.description') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            job_title: jobTitle,
            charging_station_type: chargingStationType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        const suggestions = data.suggestions;
        const suggestionsList = document.getElementById('suggestionsList');
        suggestionsList.innerHTML = '';

        suggestions.forEach((suggestion, index) => {
            const card = document.createElement('div');
            card.className = 'card mb-3';
            
            // Escape any HTML in the suggestion text
            const escapedSuggestion = suggestion
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
            
            card.innerHTML = `
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Suggestion ${index + 1}</h6>
                    <p class="card-text">${escapedSuggestion}</p>
                    <button type="button" class="btn btn-primary btn-sm" onclick="selectSuggestion(\`${escapedSuggestion}\`)">
                        Use This Description
                    </button>
                </div>
            `;
            suggestionsList.appendChild(card);
        });

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('suggestionsModal'));
        modal.show();
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        spinner.classList.add('d-none');
        btnText.textContent = 'Generate with AI';
    });
}

function selectSuggestion(suggestion) {
    // Set the value of the textarea
    document.getElementById('job_description').value = suggestion;
    
    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('suggestionsModal'));
    if (modal) {
        modal.hide();
    }
}

function getBudgetEstimate() {
    const spinner = document.getElementById('budgetLoadingSpinner');
    const btnText = document.getElementById('budgetBtnText');
    const jobDescription = document.getElementById('job_description').value;

    if (!jobDescription) {
        alert('Please generate or enter a job description first');
        return;
    }

    spinner.classList.remove('d-none');
    btnText.textContent = 'Estimating...';

    fetch("{{ route('job.ai.budget') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            job_description: jobDescription
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }

        // Extract numeric value from the estimate
        let estimate = data.estimate;
        let numericEstimate = extractNumericValue(estimate);
        
        if (numericEstimate) {
            // Animate the budget value
            animateBudgetValue(0, numericEstimate);
        } else {
            document.getElementById('estimated_budget').value = estimate;
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        spinner.classList.add('d-none');
        btnText.textContent = 'Get Budget Estimate';
    });
}

function extractNumericValue(estimate) {
    // Try to extract a numeric value or range from the text
    const matches = estimate.match(/\$?(\d{1,3}(?:,\d{3})*(?:\.\d+)?)/g);
    if (matches && matches.length > 0) {
        // Get the first number found
        let number = matches[0].replace(/[$,]/g, '');
        return parseFloat(number);
    }
    return null;
}

function animateBudgetValue(start, end) {
    const duration = 1000; // Animation duration in milliseconds
    const input = document.getElementById('estimated_budget');
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function for smooth animation
        const easeOutQuad = progress * (2 - progress);
        const current = Math.round(start + (end - start) * easeOutQuad);
        
        input.value = current.toLocaleString('en-US');
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

$(document).ready(function() {
    // Initialize Select2 with checkboxes
    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        width: '100%',
        closeOnSelect: false,
        templateResult: function(data) {
            if (!data.id) return data.text;
            
            return $('<div class="select2-option">' +
                '<input type="checkbox" ' + (data.selected ? 'checked' : '') + '/> ' +
                '<span>' + data.text + '</span>' +
                '</div>');
        }
    });

    // Initialize the map
    let map = L.map('map').setView([39.8283, -98.5795], 4); // Center of USA
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker;
    let searchTimeout;

    // Function to show loading state
    function setLoadingState(isLoading) {
        const spinner = document.getElementById('searchSpinner');
        const buttonText = document.getElementById('searchButtonText');
        if (isLoading) {
            spinner.classList.remove('d-none');
            buttonText.textContent = 'Searching';
        } else {
            spinner.classList.add('d-none');
            buttonText.textContent = 'Search';
        }
    }

    // Function to handle address search
    async function searchAddress() {
        const address = document.getElementById('installation_address').value;
        const suggestionsDiv = document.getElementById('address-suggestions');
        
        if (!address.trim()) {
            alert('Please enter an address to search');
            return;
        }

        setLoadingState(true);
        
        try {
            // Add a small delay to respect Nominatim's usage policy
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // Use Nominatim for geocoding
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&countrycodes=us&limit=5`);
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();

            // Clear previous suggestions
            suggestionsDiv.innerHTML = '';

            if (data.length > 0) {
                suggestionsDiv.style.display = 'block';
                data.forEach(result => {
                    const div = document.createElement('button');
                    div.className = 'list-group-item list-group-item-action';
                    div.innerHTML = `
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${result.display_name.split(',')[0]}</h6>
                            <small>${result.type}</small>
                        </div>
                        <small class="text-muted">${result.display_name}</small>
                    `;
                    div.onclick = () => {
                        selectAddress(result);
                        suggestionsDiv.style.display = 'none';
                    };
                    suggestionsDiv.appendChild(div);
                });
            } else {
                suggestionsDiv.innerHTML = '<div class="list-group-item text-center">No results found</div>';
                suggestionsDiv.style.display = 'block';
            }
        } catch (error) {
            console.error('Error searching address:', error);
            alert('Error searching address. Please try again.');
            suggestionsDiv.innerHTML = '<div class="list-group-item text-center text-danger">Error searching address</div>';
            suggestionsDiv.style.display = 'block';
        } finally {
            setLoadingState(false);
        }
    }

    // Function to handle address selection with animation
    function selectAddress(result) {
        const addressInput = document.getElementById('installation_address');
        const latInput = document.getElementById('latitude');
        const lonInput = document.getElementById('longitude');

        // Update form fields with animation
        addressInput.value = result.display_name;
        
        // Animate latitude and longitude updates
        const lat = parseFloat(result.lat);
        const lon = parseFloat(result.lon);
        
        animateValue(latInput, latInput.value || 0, lat, 1000);
        animateValue(lonInput, lonInput.value || 0, lon, 1000);

        // Update map with animation
        if (marker) {
            map.removeLayer(marker);
        }
        
        marker = L.marker([lat, lon]).addTo(map);
        map.flyTo([lat, lon], 16, {
            duration: 1.5,
            easeLinearity: 0.25
        });

        // Add success feedback
        addressInput.classList.add('is-valid');
        setTimeout(() => addressInput.classList.remove('is-valid'), 2000);
    }

    // Function to animate number changes
    function animateValue(element, start, end, duration) {
        start = parseFloat(start);
        const range = end - start;
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const value = start + (range * progress);
            element.value = value.toFixed(7);

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        
        requestAnimationFrame(update);
    }

    // Add event listeners with debouncing for search
    document.getElementById('searchAddress').addEventListener('click', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(searchAddress, 300);
    });
    
    document.getElementById('installation_address').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchAddress, 300);
        }
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        const suggestionsDiv = document.getElementById('address-suggestions');
        const searchInput = document.getElementById('installation_address');
        const searchButton = document.getElementById('searchAddress');
        
        if (!suggestionsDiv.contains(e.target) && 
            e.target !== searchInput && 
            e.target !== searchButton) {
            suggestionsDiv.style.display = 'none';
        }
    });

    // Add custom CSS for suggestions and animations
    const style = document.createElement('style');
    style.textContent = `
        #address-suggestions {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 4px;
            margin-top: 4px;
        }
        .list-group-item-action {
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .list-group-item-action:hover {
            background-color: #f8f9fa;
            border-left-color: #0d6efd;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        .is-valid {
            animation: validPulse 0.5s ease;
        }
        @keyframes validPulse {
            0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.2); }
            70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
            100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
        }
    `;
    document.head.appendChild(style);
});

document.addEventListener('DOMContentLoaded', function() {
    // Make entire label area clickable
    document.querySelectorAll('.form-check-label').forEach(label => {
        label.addEventListener('click', function() {
            const checkbox = document.getElementById(this.getAttribute('for'));
            checkbox.checked = !checkbox.checked;
            
            // Add animation effect
            label.closest('.form-check').style.backgroundColor = '#e9ecef';
            setTimeout(() => {
                label.closest('.form-check').style.backgroundColor = '';
            }, 200);
        });
    });

    // Add keyboard navigation
    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.checked = !this.checked;
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Add visual feedback for required fields
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        let hasError = false;
        form.querySelectorAll('[required]').forEach(function(input) {
            if (!input.value) {
                input.classList.add('is-invalid');
                hasError = true;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (hasError) {
            event.preventDefault();
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Real-time validation
    form.querySelectorAll('[required]').forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });

    // Handle the search button click
    const searchAddressBtn = document.getElementById('searchAddress');
    if (searchAddressBtn) {
        searchAddressBtn.addEventListener('click', function() {
            const addressInput = document.getElementById('installation_address');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            
            if (!addressInput.value) {
                alert("Please enter an address to search");
                return;
            }
            
            // Show spinner
            const searchSpinner = document.getElementById('searchSpinner');
            const searchButtonText = document.getElementById('searchButtonText');
            if (searchSpinner) searchSpinner.classList.remove('d-none');
            if (searchButtonText) searchButtonText.textContent = 'Searching...';
            
            // Use Geocoding API to get coordinates
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': addressInput.value }, function(results, status) {
                // Hide spinner
                if (searchSpinner) searchSpinner.classList.add('d-none');
                if (searchButtonText) searchButtonText.textContent = 'Search';
                
                if (status === 'OK' && results[0]) {
                    const location = results[0].geometry.location;
                    
                    // Update form fields
                    latitudeInput.value = location.lat();
                    longitudeInput.value = location.lng();
                    
                    // Trigger validation
                    latitudeInput.dispatchEvent(new Event('input'));
                    longitudeInput.dispatchEvent(new Event('input'));
                    
                    // Update map if available
                    if (typeof map !== 'undefined') {
                        const newLatLng = [location.lat(), location.lng()];
                        
                        if (typeof marker !== 'undefined') {
                            marker.setLatLng(newLatLng);
                        } else {
                            marker = L.marker(newLatLng).addTo(map);
                        }
                        
                        map.setView(newLatLng, 15);
                    }
                    
                    // Update the address field with formatted address
                    addressInput.value = results[0].formatted_address;
                } else {
                    alert('Could not find coordinates for this address. Please try a different address.');
                }
            });
        });
    }
});
</script>

<style>
.alert {
    margin-bottom: 20px;
}

.invalid-feedback {
    display: block;
}

.form-control.is-invalid,
.form-control.is-valid {
    transition: border-color 0.15s ease-in-out;
}

.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-valid {
    border-color: #198754;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Improve the appearance of Google Places autocomplete */
.pac-container {
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    border-radius: 4px;
    font-family: inherit;
    z-index: 10000; /* Make sure it displays above other elements */
}

.pac-item {
    padding: 8px 15px;
    cursor: pointer;
    font-size: 14px;
    border-top: 1px solid #e6e6e6;
}

.pac-item:hover {
    background-color: #f5f5f5;
}

.pac-item-query {
    font-size: 14px;
    color: #333;
}

/* If your form is in a modal, ensure z-index is higher */
.modal {
    z-index: 9000;
}
</style>
@endsection