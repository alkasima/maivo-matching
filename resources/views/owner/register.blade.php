@include('layouts.header')
<main>
    <h1 class="page-title">Join MAIVO Network</h1>
    <p class="page-subtitle">Connect with qualified service providers and streamline your EV charging infrastructure
    </p>
    
   
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="section-title">Select Account Type</h2>
    <div class="account-types">
        <div class="account-option" data-account-type="business">
            <div class="account-option-title">
                <span class="account-option-icon">🏢</span>
                Business Account
            </div>
            <p class="account-option-desc">For property owners, operators, and service providers</p>
            <div class="check-icon hidden">✓</div>
        </div>
        <div class="account-option selected" data-account-type="individual">
            <div class="account-option-title">
                <span class="account-option-icon">👤</span>
                Individual Account
            </div>
            <p class="account-option-desc">For independent contractors and professionals</p>
            <div class="check-icon">✓</div>
        </div>
    </div>

    <div class="registration-form">
        <form method="POST" action="{{ route('owner.register') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="account_type" id="account_type" value="{{ old('account_type', 'individual') }}">

            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="first_name" class="form-control" placeholder="Value"
                        value="{{ old('first_name') }}" required>
                    @error('first_name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="last_name" class="form-control" placeholder="Value"
                        value="{{ old('last_name') }}" required>
                    @error('last_name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Value"
                    value="{{ old('email') }}" required>
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Value" required>
                @error('password')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group business-fields hidden" style="margin-top: 20px;">
                <label for="company">Company Name</label>
                <input type="text" id="company" name="company_name" class="form-control" placeholder="Value"
                    value="{{ old('company_name') }}">
                @error('company_name')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group business-fields hidden" style="margin-top: 20px;">
                <label for="businessType">Business Type</label>
                <input type="text" id="businessType" name="business_type" class="form-control" placeholder="Value"
                    value="{{ old('business_type') }}">
                @error('business_type')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check">
                <input type="checkbox" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                <label for="terms">I agree to the Terms of Service and Privacy Policy</label>
                @error('terms')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>
        
        <div class="auth-links mt-3 text-center">
            <p>Already have an account? <a href="{{ route('user.login') }}" class="login-link">Login here</a></p>
        </div>
    </div>
</main>
@include('layouts.footer')