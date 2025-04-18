<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

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

        // Get verified contractors only deactivated for testing
        //$query->where('status', 'verified');

        $contractors = $query->paginate(3);

        return view('marketplace.index', compact('contractors'));
    }


    public function matchContractors(Request $request)
    {
        // Get the authenticated owners
        $owner = Auth::user()->owner;
        
        if (!$owner) {
            return redirect()->route('marketplace.index')
                ->with('error', 'You must be logged in as an owner to use the matching feature');
        }
        
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
            $score += min($contractor->years_of_experience * 2, 20);
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

    
    //Get AI-based matches using Gemini
    public function getAiMatches(Request $request)
    {
        // Check if this is an auto-suggest request 
        $isAutoSuggest = $request->input('auto_suggest', false);
        
        // Get the query from the request what user type
        $ownerNeeds = $request->input('needs');
        
        if (empty($ownerNeeds) && !$isAutoSuggest) {
            return response()->json([
                'success' => false,
                'message' => 'Please describe what services you need'
            ], 400);
        }
        
        $contractors = Contractor::all();
        
        // If no contractors found, return empty response
        if ($contractors->isEmpty()) {
            return response()->json([
                'success' => true,
                'matches' => []
            ]);
        }
        
        // For auto-suggest, get top 3 contractors based on different criteria
        if ($isAutoSuggest) {
            $topContractors = Contractor::orderBy('years_of_experience', 'desc')
                                        ->limit(3)
                                        ->get();
            
            $matches = [];
            foreach ($topContractors as $contractor) {
                $matches[] = [
                    'contractor' => $contractor,
                    'match_score' => rand(85, 97), // Random high score for top picks
                    'match_reason' => $this->generateAutoSuggestReason($contractor)
                ];
            }
            
            return response()->json([
                'success' => true,
                'matches' => $matches
            ]);
        }
        
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
        
        // Call AI service
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

    
     //Call Gemini AI for matching contractors
    private function callAiMatchingService($ownerNeeds, $contractors)
    {
        // If GEMINI_API_KEY is not set, use fallback matching algorithm
        if (!env('GEMINI_API_KEY')) {
            return $this->fallbackMatchingAlgorithm($ownerNeeds, $contractors);
        }
        
        // Prepare the data for Gemini
        $contractorJson = json_encode($contractors);
        
        $prompt = <<<EOT
You are an AI assistant helping match contractors to homeowners' needs for EV charger installation.

User Need: {$ownerNeeds}

Available Contractors (in JSON format):
{$contractorJson}

For each contractor, analyze how well they match the user's needs. Return exactly 3 contractors with:
1. Contractor ID 
2. Match score (percentage between 0-100)
3. A brief, specific explanation of why they match

Format your response as valid JSON with this structure:
[
  {
    "contractor_id": 123,
    "score": 85,
    "reason": "Specializes in residential EV charger installation with 5+ years experience"
  },
  ...
]
EOT;

        try {
            // Set up Gemini API request
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => env('GEMINI_API_KEY')
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'topP' => 0.8,
                        'topK' => 40
                    ]
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            // Extract the generated response
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
                
                preg_match('/\[\s*\{.*?\}\s*\]/s', $aiResponse, $matches);
                if (!empty($matches)) {
                    $aiMatches = json_decode($matches[0], true);
                    
                    if (is_array($aiMatches) && count($aiMatches) > 0) {
                        return $aiMatches;
                    }
                }
            }
            
            // If it did not get from Gemini response, fall back to my algorithm
            return $this->fallbackMatchingAlgorithm($ownerNeeds, $contractors);
            
        } catch (\Exception $e) {
            \Log::error('Gemini AI matching error: ' . $e->getMessage());
            // If API call fails, fall back to my algorithm
            return $this->fallbackMatchingAlgorithm($ownerNeeds, $contractors);
        }
    }

    /**
     * Fallback algorithm in case Gemini API fails
     */
    private function fallbackMatchingAlgorithm($ownerNeeds, $contractors)
    {
        $matches = [];
        
        // Extract keywords from owner needs
        $keywords = $this->extractKeywords($ownerNeeds);
        
        // Check if the search is relevant to EV services
        $relevantSearch = $this->isRelevantToEVServices($ownerNeeds, $keywords);
        
        // If the search isn't relevant to EV services, return empty matches
        if (!$relevantSearch) {
            return []; // Return empty array for irrelevant searches
        }
        
        foreach ($contractors as $contractor) {
            // Start with a LOWER base score
            $score = 30;
            $matchReasons = [];
            
            // Check for keyword matches in contractor data
            foreach ($keywords as $keyword) {
                if (isset($contractor['services']) && 
                    stripos($contractor['services'], $keyword) !== false) {
                    $score += 10; 
                    $matchReasons[] = "Offers " . ucfirst($keyword) . " services";
                }
                
                if (isset($contractor['company']) && 
                    stripos($contractor['company'], $keyword) !== false) {
                    $score += 8; 
                    $matchReasons[] = "Company specializes in " . ucfirst($keyword);
                }
                
                if (isset($contractor['overview']) && 
                    stripos($contractor['overview'], $keyword) !== false) {
                    $score += 5;
                    $matchReasons[] = "Experience with " . ucfirst($keyword) . " projects";
                }
            }
            
            // Add points for experience - scale more gradually
            if (isset($contractor['experience']) && $contractor['experience'] > 0) {
                $expPoints = min(12, $contractor['experience']);
                $score += $expPoints;
                $matchReasons[] = $contractor['experience'] . "+ years of industry experience";
            }
            
            // Verification bonus
            if (isset($contractor['verified']) && $contractor['verified']) {
                $score += 8; 
                $matchReasons[] = "Verified professional with proven track record";
            }
            
            // Ensure score doesn't exceed 95% for algorithm matches
            $score = min(95, $score);
            
            if (empty($matchReasons)) {
                $matchReasons[] = "Potential match for your needs";
            }
            
            $matchReasons = array_slice($matchReasons, 0, 3);
            
            $matches[] = [
                'contractor_id' => $contractor['id'],
                'score' => $score,
                'reason' => implode('. ', $matchReasons)
            ];
        }
        
        // Sort by score (highest first)
        usort($matches, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return array_slice($matches, 0, 3);
    }

    /**
     * Check if the search query is relevant to EV services
     */
    private function isRelevantToEVServices($query, $keywords)
    {
        // Define EV-related terminology
        $evTerms = ['ev', 'electric', 'vehicle', 'charger', 'charging', 'tesla', 'level 2', 
                    'car', 'volt', 'battery', 'power', 'electrical', 'outlet', 'installation'];
        
        // Check if any EV terms are in the query or keywords
        $query = strtolower($query);
        
        foreach ($evTerms as $term) {
            if (stripos($query, $term) !== false) {
                return true;
            }
        }
        
        // Check if any of the extracted keywords match EV terms
        foreach ($keywords as $keyword) {
            if (in_array(strtolower($keyword), $evTerms)) {
                return true;
            }
        }
        
        // If search mentions competitors or similar services, it's probably relevant
        $related = ['electrician', 'contractor', 'install', 'installer', 'energy', 'solar'];
        foreach ($related as $term) {
            if (stripos($query, $term) !== false) {
                return true; 
            }
        }
        
        // Default - no relevant terms found
        return false;
    }

    /**
     * Extract keywords from the owner needs text
     */
    private function extractKeywords($text)
    {
        // Relevant keywords for EV and contractor services
        $relevantTerms = [
            'charger', 'charging', 'station', 'ev', 'electric', 'vehicle', 
            'installation', 'install', 'maintenance', 'repair', 'upgrade',
            'residential', 'commercial', 'industrial', 'home', 'business',
            'fast', 'level 2', 'level 3', 'tesla', 'certification'
        ];
        
        // Convert to lowercase
        $text = strtolower($text);
        
        // Extract matched keywords
        $matches = [];
        foreach ($relevantTerms as $term) {
            if (stripos($text, $term) !== false) {
                $matches[] = $term;
            }
        }
        
        // Add additional unique words (nouns and adjectives)
        $words = str_word_count($text, 1);
        foreach ($words as $word) {
            if (strlen($word) > 3 && !in_array($word, $relevantTerms) && !in_array($word, $matches)) {
                $matches[] = $word;
            }
        }
        
        return array_slice($matches, 0, 6); // Limit to 6 keywords
    }

    private function generateAutoSuggestReason($contractor)
    {
        $reasons = [
            "Highly experienced with " . $contractor->years_of_experience . "+ years in the industry",
            "Specialist in " . $contractor->service_title . " with excellent reviews",
            "Top-rated professional offering comprehensive services",
            "Known for quality installations and responsive customer service",
            "Consistently delivers projects on time and within budget"
        ];
        
        return $reasons[array_rand($reasons)];
    }
}
