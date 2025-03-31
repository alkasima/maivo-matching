<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ContractorController extends Controller
{
    public function showRegistrationForm()
    {
        return view('contractor.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:contractors',
            'phone' => 'required|string|max:20',
            'company_address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        $contractor = Contractor::create([
            'company_name' => $validated['company_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company_address' => $validated['company_address'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
        ]);

        // Redirect to the vetting process page
        return redirect()->route('contractor.vetting')
            ->with('success', 'Account created successfully! Please complete the vetting process.');
    }
} 