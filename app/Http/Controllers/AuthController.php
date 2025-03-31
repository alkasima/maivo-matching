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

        return redirect()->back()->with('success', 'Registration successful!');
    }

    public function loginForm()
    {
        return view('login');
    }


    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'account_type' => 'required|string|in:owner,service',
        ]);

        $accountType = $request->input('account_type');

        if ($accountType == 'owner') {
            $user = Owner::where('email', $request->email)->first();
        } else {
            $user = Contractor::where('email', $request->email)->first();
        }


        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        }

        if ($accountType == 'owner') {
            Auth::guard('owner')->login($user);
            return redirect()->intended('/owner/dashboard');
        } else {
            Auth::guard('contractor')->login($user);
            return redirect()->intended('/provider/dashboard');
        }
    }
    public function register(Request $request)
    {
        return view('owner.register');
    }
}
