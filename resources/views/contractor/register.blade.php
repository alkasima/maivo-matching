<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Provider Registration - Mavic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-md-5">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="Mavic" height="40">
                    </a>
                </div>

                <!-- Progress Steps -->
                <div class="progress-steps mb-4">
                    <div class="d-flex justify-content-between position-relative">
                        <div class="progress-line"></div>
                        <div class="step active">
                            <div class="step-circle">
                                <div class="inner-circle"></div>
                            </div>
                            <div class="step-label">Account Creation</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">
                                <div class="inner-circle"></div>
                            </div>
                            <div class="step-label">Vetting Process</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">
                                <div class="inner-circle"></div>
                            </div>
                            <div class="step-label">Verification</div>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Create Service Provider Account</h4>
                        
                        <form method="POST" action="{{ route('contractor.register') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" 
                                       value="{{ old('company_name') }}" placeholder="Value">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ old('first_name') }}" placeholder="Value">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ old('last_name') }}" placeholder="Value">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email') }}" placeholder="Value">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ old('phone') }}" placeholder="Value">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="company_address" class="form-label">Company Address</label>
                                <input type="text" class="form-control" id="company_address" name="company_address" 
                                       value="{{ old('company_address') }}" placeholder="Value">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Value">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Proceed</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
.progress-steps {
    padding: 20px 0;
}

.progress-line {
    position: absolute;
    top: 15px;
    left: 50px;
    right: 50px;
    height: 2px;
    background: #e0e0e0;
    z-index: 1;
}

.step {
    position: relative;
    z-index: 2;
    width: 100px;
    text-align: center;
}

.step-circle {
    width: 32px;
    height: 32px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 50%;
    margin: 0 auto 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.inner-circle {
    width: 12px;
    height: 12px;
    background: #e0e0e0;
    border-radius: 50%;
}

.step.active .step-circle {
    border-color: #0d6efd;
}

.step.active .inner-circle {
    background: #0d6efd;
}

.step-label {
    color: #6c757d;
    font-size: 14px;
}

.step.active .step-label {
    color: #0d6efd;
}

.form-control {
    padding: 0.75rem 1rem;
}

.form-control::placeholder {
    color: #ccc;
}

.btn-primary {
    padding: 0.75rem 1.5rem;
}

.card {
    border-radius: 10px;
}

@media (max-width: 768px) {
    .progress-line {
        left: 30px;
        right: 30px;
    }
    
    .step {
        width: 80px;
    }
    
    .step-label {
        font-size: 12px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 