@include('layout.header')
<main>
    <h1 class="page-title">Login To MAIVO Network</h1>
    </p>

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

            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>
    </div>
</main>
@include('layout.footer')