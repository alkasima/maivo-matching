<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JobController extends Controller
{
   

    public function create()
    {
        return view('job.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'charging_station_type' => 'required|string',
            'installation_location_type' => 'required|string',
            'installation_complexity' => 'required|string',
            'job_duration_estimate' => 'required|string',
            'job_description' => 'nullable|string',
            'installation_address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'preferred_start_date' => 'required|date',
            'job_flexibility' => 'required|string',
            'experience_level' => 'required|string',
            'pricing_preference' => 'required|string',
            'payment_terms' => 'required|string',
            'owner_name' => 'required|string',
            'contact_email' => 'required|email',
            'contact_method' => 'required|string',
            'estimated_budget' => 'nullable|numeric',
            'station_model' => 'nullable|string',
            'company_name' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'past_project_references' => 'nullable|string',
            'similar_jobs_completed' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'license_certifications' => 'nullable|array',
            'specific_skills' => 'nullable|array',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'required' => 'The :attribute field is required.',
            'email' => 'Please enter a valid email address.',
            'numeric' => 'The :attribute must be a number.',
            'date' => 'Please enter a valid date.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'supporting_documents.*.max' => 'Each file may not be greater than 10MB.',
        ]);

        try {
            // Add owner_id to validated data
            $validated['owner_id'] = auth()->id();

            // Handle file uploads
            if ($request->hasFile('supporting_documents')) {
                $paths = [];
                foreach ($request->file('supporting_documents') as $file) {
                    $path = $file->store('supporting_documents', 'public');
                    $paths[] = $path;
                }
                $validated['supporting_documents'] = json_encode($paths);
            }

            // Create job posting
            JobPosting::create($validated);

            return redirect()
                ->route('job.create')
                ->with('success', 'Job posting created successfully! Your job has been posted and is now visible to contractors.');

        } catch (\Exception $e) {
            \Log::error('Error creating job posting: ' . $e->getMessage());
            
            return redirect()
                ->route('job.create')
                ->withInput()
                ->withErrors(['error' => 'There was an error creating your job posting. Please try again.']);
        }
    }

    public function getJobDescription(Request $request)
    {
        try {
            $jobTitle = $request->job_title;
            $chargingStationType = $request->charging_station_type;

            // Prompts for Gemini
            $prompts = [
                "Generate a professional and concise job description for an EV installation job:\nJob Title: {$jobTitle}\nCharging Station Type: {$chargingStationType}\n\nFormat the response in a clear, professional manner with sections for Overview, Responsibilities, and Requirements.",
                "Generate a technical-focused job description for an EV installation job:\nJob Title: {$jobTitle}\nCharging Station Type: {$chargingStationType}\n\nEmphasize technical requirements and specifications. Format with sections for Technical Requirements, Installation Process, and Safety Protocols.",
                "Generate a qualification-focused job description for an EV installation job:\nJob Title: {$jobTitle}\nCharging Station Type: {$chargingStationType}\n\nFocus on required certifications, experience, and skills. Include sections for Required Qualifications, Experience, and Professional Certifications.",
            ];

            $suggestions = [];
            foreach ($prompts as $prompt) {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => env('GEMINI_API_KEY'),
                ])->post('https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent', [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ],
                ]);

                if (!$response->successful()) {
                    \Log::error('API Response Error:', ['response' => $response->json()]);
                    throw new \Exception('Failed to generate job description');
                }

                $suggestions[] = $this->extractContent($response);
            }

            return response()->json(['suggestions' => $suggestions]);
        } catch (\Exception $e) {
            \Log::error('Error generating job description: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBudgetEstimate(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => env('GEMINI_API_KEY'),
            ])->post('https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent', [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "Based on the following job description, provide only a numeric budget estimate (just the number) for an EV charging station installation. Include both materials and labor costs. Provide a single number, not a range:\n\n{$request->job_description}"
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3, // Lower temperature for more consistent numeric responses
                    'maxOutputTokens' => 100,
                ]
            ]);

            if (!$response->successful()) {
                \Log::error('Budget API Response Error:', ['response' => $response->json()]);
                throw new \Exception('Failed to generate budget estimate');
            }

            $budget = $this->extractContent($response);
            
            // Clean up the response to ensure it's numeric
            $numericBudget = preg_replace('/[^0-9.]/', '', $budget);
            if (!empty($numericBudget)) {
                return response()->json(['estimate' => $numericBudget]);
            }

            return response()->json(['estimate' => 'Could not generate a numeric estimate']);
        } catch (\Exception $e) {
            \Log::error('Error generating budget estimate: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function extractContent($response)
    {
        $data = $response->json();
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($data['candidates'][0]['content']['parts'][0]['text']);
        }
        \Log::error('Unexpected API response format:', ['response' => $data]);
        throw new \Exception('Unexpected API response format');
    }

    public function myJobs()
    {
        $jobs = JobPosting::where('owner_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('job.my-jobs', compact('jobs'));
    }

    public function show(JobPosting $job)
    {
        // Check if the authenticated user owns this job
        if ($job->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('job.show', compact('job'));
    }
}
