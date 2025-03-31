<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAIVO Network - Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    /* ... (Your existing CSS) ... */
    .error {
        color: red;
        font-size: 0.8rem;
        margin-top: 5px;
    }
    </style>
    <style>
    /* Reset and base styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    body {
        background-color: #fff;
        color: #333;
        line-height: 1.6;
    }

    /* Header styles */
    header {
        background-color: #0a0033;
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        display: flex;
        align-items: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
        letter-spacing: 1px;
    }

    .logo-icon {
        margin-left: 5px;
        font-size: 20px;
        transform: rotate(45deg);
    }

    .login-btn {
        background-color: #3b7aff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 25px;
        font-size: 16px;
        cursor: pointer;
        font-weight: 500;
    }

    /* Main content styles */
    main {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .page-title {
        text-align: center;
        margin-bottom: 10px;
        color: #333;
        font-size: 28px;
    }

    .page-subtitle {
        text-align: center;
        margin-bottom: 30px;
        color: #555;
        font-size: 16px;
    }

    .section-title {
        margin: 25px 0 15px;
        color: #333;
        font-size: 18px;
    }

    /* Account type selection */
    .account-types {
        display: flex;
        gap: 20px;
        margin: 25px 0;
    }

    .account-option {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        position: relative;
    }

    .account-option.selected {
        border: 2px solid #3b7aff;
        background-color: #f5f9ff;
    }

    .account-option-title {
        font-weight: 600;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }

    .account-option-icon {
        margin-right: 10px;
        font-size: 20px;
        color: #555;
    }

    .account-option-desc {
        font-size: 14px;
        color: #666;
    }

    .check-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        color: #3b7aff;
        background-color: #e6f0ff;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Form styles */
    .registration-form {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 30px;
        margin-top: 20px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin: 25px 0;
    }

    .form-check input {
        margin-right: 10px;
        width: 18px;
        height: 18px;
    }

    .submit-btn {
        background-color: #3b7aff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 15px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        font-weight: 500;
    }

    .hidden {
        display: none;
    }
    </style>
</head>

<body>
    <header>
        
        <a href="/">
        <div class="logo">
            MAVIO<span class="logo-icon">✈</span>
        </div>
        </a>


        <a href="/login"><button class="login-btn">Login</button></a>
    </header>