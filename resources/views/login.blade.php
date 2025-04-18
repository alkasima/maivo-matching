@include('layouts.header')
<main>
    <h1 class="page-title">Login to Your Account</h1>
    <p class="page-subtitle">Access your MAIVO Network dashboard</p>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="section-title">Select Account Type</h2>
    <div class="account-types">
        <div class="account-option" data-account-type="owner">
            <div class="account-option-title">
                <span class="account-option-icon">👤</span>
                Owner Account
            </div>
            <div class="check-icon hidden">✓</div>
        </div>
        <div class="account-option selected" data-account-type="service">
            <div class="account-option-title">
            <span class="account-option-icon">🏢</span>
                Service Provider Account
            </div>
            <div class="check-icon">✓</div>
        </div>
    </div>

    <div class="registration-form">
        <form action="{{ route('user.login') }}" method="POST">
            @csrf
            <input type="hidden" name="account_type" id="account_type" value="{{ old('account_type', 'individual') }}">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Value"
                    value="{{ old('email') }}" required>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Value" required>
            </div>

            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>
        
        <div class="auth-links mt-3 text-center">
            <p>Don't have an account? <a href="{{ route('owner.register') }}" class="register-link">Register now</a></p>
        </div>
    </div>
</main>
@include('layouts.footer')