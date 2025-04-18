<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Owner;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view('owner.register');
    }
    public function registerOwner(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:owners',
            'password' => 'required|string|min:6',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
        ]);

        $owner = Owner::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'business_type' => $request->business_type,
        ]);

        return redirect()->route('user.login')->with('success', 'Registration successful! Please login with your new account.');
    }

    public function loginForm()
    {
        return view('login');
    }


    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Debug: Check if user exists in the database
        $ownerExists = Owner::where('email', $request->email)->exists();
        $contractorExists = Contractor::where('email', $request->email)->exists();
        
        \Log::info("Login attempt for {$request->email}. Owner exists: " . ($ownerExists ? 'Yes' : 'No') . 
                   ", Contractor exists: " . ($contractorExists ? 'Yes' : 'No'));
        
        // Get account type or try both if not specified
        $accountType = $request->input('account_type', null);
        
        // Try owner login if account_type is owner or not specified
        if ($accountType === 'owner' || $accountType === null) {
            if (Auth::guard('owner')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ], $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('owner.dashboard'));
            }
        }
        
        // Try contractor login if account_type is service or not specified
        if ($accountType === 'service' || $accountType === null) {
            if (Auth::guard('contractor')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ], $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('contractor.dashboard'));
            }
        }
        
        // If reach here, authentication failed
        return back()->with('error', 'Invalid login credentials. Please try again.');
    }
    public function register(Request $request)
    {
        return view('owner.register');
    }
}
