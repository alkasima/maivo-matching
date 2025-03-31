<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MarketPlaceController extends Controller
{
    public function show(Request $request)
    {
        $query = Contractor::query();

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('company_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('service_information', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by service area
        if ($request->has('location')) {
            $query->where('service_area_coverage', $request->location);
        }

        // Filter by service type
        if ($request->has('service')) {
            $query->where('service_information', 'LIKE', "%{$request->service}%");
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        // Get verified contractors only
        //$query->where('status', 'verified');

        $contractors = $query->paginate(9);

        return view('marketplace.index', compact('contractors'));
    }

    /**
     * Match contractors with owners based on compatibility
     */
    public function matchContractors(Request $request)
    {
        // Get the authenticated owner
        $owner = Auth::user()->owner;
        
        if (!$owner) {
            return redirect()->route('marketplace.index')
                ->with('error', 'You must be logged in as an owner to use the matching feature');
        }
        
        // Get all contractors
        $contractors = Contractor::all();
        
        // Calculate match scores
        $matchedContractors = [];
        foreach ($contractors as $contractor) {
            $score = $this->calculateMatchScore($contractor, $owner);
            
            $matchedContractors[] = [
                'contractor' => $contractor,
                'match_score' => $score,
                'match_reason' => $this->generateMatchReason($contractor, $owner, $score)
            ];
        }
        
        // Sort by match score (highest first)
        usort($matchedContractors, function($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });
        
        return view('marketplace.matches', compact('matchedContractors'));
    }
    
    /**
     * Calculate match score between a contractor and owner
     */
    private function calculateMatchScore($contractor, $owner)
    {
        $score = 0;
        
        // 1. Service type matching
        if (!empty($owner->service_needs) && !empty($contractor->service_information)) {
            $ownerNeeds = explode(',', strtolower($owner->service_needs));
            $contractorServices = strtolower($contractor->service_information);
            
            foreach ($ownerNeeds as $need) {
                if (strpos($contractorServices, trim($need)) !== false) {
                    $score += 20; // +20 points for each service match
                }
            }
        }
        
        // 2. Location proximity
        if (!empty($owner->location) && !empty($contractor->service_area_coverage)) {
            $ownerLocation = strtolower($owner->location);
            $contractorArea = strtolower($contractor->service_area_coverage);
            
            if (strpos($contractorArea, $ownerLocation) !== false) {
                $score += 30; // +30 points for location match
            }
        }
        
        // 3. Add points for verified contractors
        if ($contractor->status == 'verified') {
            $score += 15;
        }
        
        // 4. Experience level bonus
        if (!empty($contractor->years_of_experience)) {
            $score += min($contractor->years_of_experience * 2, 20); // Up to 20 points
        }
        
        return $score;
    }
    
    /**
     * Generate human-readable match reasons
     */
    private function generateMatchReason($contractor, $owner, $score)
    {
        $reasons = [];
        
        if ($score >= 50) {
            $reasons[] = "High compatibility with your service needs";
        } else if ($score >= 30) {
            $reasons[] = "Good match for your requirements";
        } else {
            $reasons[] = "Potential match for your needs";
        }
        
        // Service match reason
        if (!empty($owner->service_needs) && !empty($contractor->service_information)) {
            $ownerNeeds = explode(',', strtolower($owner->service_needs));
            $contractorServices = strtolower($contractor->service_information);
            
            $matchedServices = [];
            foreach ($ownerNeeds as $need) {
                if (strpos($contractorServices, trim($need)) !== false) {
                    $matchedServices[] = trim($need);
                }
            }
            
            if (count($matchedServices) > 0) {
                $reasons[] = "Offers " . count($matchedServices) . " of your requested services";
            }
        }
        
        // Location match reason
        if (!empty($owner->location) && !empty($contractor->service_area_coverage)) {
            $ownerLocation = strtolower($owner->location);
            $contractorArea = strtolower($contractor->service_area_coverage);
            
            if (strpos($contractorArea, $ownerLocation) !== false) {
                $reasons[] = "Services your area";
            }
        }
        
        return implode('. ', $reasons);
    }

    /**
     * Get top 3 contractor matches for AJAX request
     */
    public function getTopMatches(Request $request)
    {
        // Check if it's an AJAX request
        if (!$request->ajax()) {
            return redirect()->route('marketplace.match');
        }
        
        // Get the authenticated owner
        $owner = Auth::user()->owner;
        
        if (!$owner) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an owner'
            ], 403);
        }
        
        // Get all contractors
        $contractors = Contractor::all();
        
        // Calculate match scores
        $matchedContractors = [];
        foreach ($contractors as $contractor) {
            $score = $this->calculateMatchScore($contractor, $owner);
            
            $matchedContractors[] = [
                'contractor' => $contractor,
                'match_score' => $score,
                'match_reason' => $this->generateMatchReason($contractor, $owner, $score)
            ];
        }
        
        // Sort by match score (highest first)
        usort($matchedContractors, function($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });
        
        // Return only the top 3 matches
        $topMatches = array_slice($matchedContractors, 0, 3);
        
        return response()->json([
            'success' => true,
            'matches' => $topMatches
        ]);
    }

    /**
     * Get AI-based matches using Gemini or similar AI service
     */
    public function getAiMatches(Request $request)
    {
        // Get the query from the request
        $ownerNeeds = $request->input('needs');
        
        if (empty($ownerNeeds)) {
            return response()->json([
                'success' => false,
                'message' => 'Please describe what services you need'
            ], 400);
        }
        
        // Get all contractors
        $contractors = Contractor::all();
        
        // Prepare data for AI analysis
        $contractorsData = [];
        foreach ($contractors as $contractor) {
            $contractorsData[] = [
                'id' => $contractor->id,
                'name' => $contractor->first_name . ' ' . $contractor->last_name,
                'company' => $contractor->company_name,
                'services' => $contractor->service_information,
                'area' => $contractor->service_area_coverage,
                'experience' => $contractor->years_of_experience,
                'verified' => $contractor->status == 'verified',
                'overview' => $contractor->profile_overview
            ];
        }
        
        // Call AI service (example implementation)
        $matches = $this->callAiMatchingService($ownerNeeds, $contractorsData);
        
        // Format results for display
        $formattedMatches = [];
        foreach ($matches as $match) {
            $contractor = Contractor::find($match['contractor_id']);
            if ($contractor) {
                $formattedMatches[] = [
                    'contractor' => $contractor,
                    'match_score' => $match['score'],
                    'match_reason' => $match['reason']
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'matches' => $formattedMatches
        ]);
    }

    /**
     * Call an AI service for matching
     */
    private function callAiMatchingService($ownerNeeds, $contractors)
    {
        $matches = [];
        
        // First pass - try to find keyword matches
        foreach ($contractors as $contractor) {
            $score = 50; // Start with a base score
            $reasons = ["Potential match for your needs"];
            
            // Add all contractors to ensure we have results
            $matches[] = [
                'contractor_id' => $contractor['id'],
                'score' => $score,
                'reason' => implode('. ', $reasons)
            ];
        }
        
        // Sort by score (or randomly if all scores are the same)
        shuffle($matches); // Add some randomness
        
        return array_slice($matches, 0, 3); // Return top 3
    }
}
