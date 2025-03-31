<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MarketPlaceController;
use App\Http\Controllers\ContractorController;

Route::get('/', function () {
    return view('owner.register');
});

Route::get('/owner/register', [AuthController::class, 'register'])->name('owner.register');
Route::post('/owner/register', [AuthController::class, 'registerOwner'])->name('owner.register.store');

Route::get('login', [AuthController::class, 'loginForm'])->name('user.login');
Route::post('login', [AuthController::class, 'loginUser'])->name('auth.login');




// Owner Routes Group
Route::middleware(['auth:owner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/job/create', [JobController::class, 'create'])->name('job.create');
    Route::post('/job', [JobController::class, 'store'])->name('job.store');
    Route::post('/job/ai/description', [JobController::class, 'getJobDescription'])->name('job.ai.description');
    Route::post('/job/ai/budget', [JobController::class, 'getBudgetEstimate'])->name('job.ai.budget');

    Route::get('/my-jobs', [JobController::class, 'myJobs'])->name('job.my-jobs');
    Route::get('/job/{job}', [JobController::class, 'show'])->name('job.show');

    Route::get('/marketplace', [MarketPlaceController::class, 'show'])->name('marketplace.index');
    Route::get('/marketplace/match', [MarketPlaceController::class, 'matchContractors'])->name('marketplace.match');
    Route::get('/marketplace/match/top', [MarketPlaceController::class, 'getTopMatches'])->name('marketplace.match.top');
    Route::post('/marketplace/match/ai', [MarketPlaceController::class, 'getAiMatches'])->name('marketplace.match.ai');

    

    
    Route::get('/settings', function () {
        return view('owner.settings'); 
    })->name('owner.settings');
});


// Route::get('/job/create', [JobController::class, 'create'])->name('job.create');
// Route::post('/job/store', [JobController::class, 'store'])->name('job.store');
// Route::post('/job/ai-description', [JobController::class, 'getJobDescription'])->name('job.ai.description');
// Route::post('/job/ai-budget', [JobController::class, 'getBudgetEstimate'])->name('job.ai.budget');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/job/create', [JobController::class, 'create'])->name('job.create');
//     Route::post('/job', [JobController::class, 'store'])->name('job.store');
//     Route::post('/job/ai/description', [JobController::class, 'getJobDescription'])->name('job.ai.description');
//     Route::post('/job/ai/budget', [JobController::class, 'getBudgetEstimate'])->name('job.ai.budget');
// });

Route::get('/provider/dashboard', function () {
    return 'provider dashboard';
})->middleware('auth:contractor');

Route::get('/contractor/register', [ContractorController::class, 'showRegistrationForm'])->name('contractor.register');
Route::post('/contractor/register', [ContractorController::class, 'register']);
