@include('layout.header')
<main>
    <h1 class="page-title">Your Matched Service Providers</h1>
    <p class="page-subtitle">We've analyzed your needs and found these recommended contractors</p>
    
    <div class="filter-container">
        <a href="{{ route('marketplace.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to All Contractors
        </a>
    </div>

    <div class="contractors-grid">
        @forelse ($matchedContractors as $match)
            @php $contractor = $match['contractor']; @endphp
            <div class="contractor-card">
                <div class="contractor-match-score">{{ $match['match_score'] }}% Match</div>
                <div class="contractor-header">
                    <h3>{{ $contractor->first_name }} {{ $contractor->last_name }}</h3>
                    @if($contractor->company_name)
                        <p class="company-name">{{ $contractor->company_name }}</p>
                    @endif
                </div>
                <div class="contractor-body">
                    <div class="contractor-service">
                        <strong>Services:</strong> {{ $contractor->service_information }}
                    </div>
                    <div class="contractor-area">
                        <strong>Service Area:</strong> {{ $contractor->service_area_coverage }}
                    </div>
                    <div class="contractor-match-reason">
                        <strong>Why we matched:</strong> {{ $match['match_reason'] }}
                    </div>
                </div>
                <div class="contractor-footer">
                    <a href="#" class="btn btn-primary contact-btn" data-contractor-id="{{ $contractor->id }}">
                        Contact Provider
                    </a>
                </div>
            </div>
        @empty
            <div class="no-results">
                <p>No matches found. Please try adjusting your preferences or browse all contractors.</p>
            </div>
        @endforelse
    </div>
</main>
@include('layout.footer') 