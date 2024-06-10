<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\KeywordPositionController;
use App\Http\Controllers\KeywordSearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\DomainController;

// Welcome route
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard route with middleware
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // API routes
    Route::prefix('api')->group(function () {
        Route::get('keywords', [KeywordController::class, 'json']);
        Route::get('domains', [DomainController::class, 'json']);
        Route::get('keyword-positions', [KeywordPositionController::class, 'json'])->name('keyword-positions.json');
        Route::post('keyword-positions/search', [KeywordSearchController::class, 'search']);
    });

    // Keyword positions routes
    Route::resource('keyword-positions', KeywordPositionController::class);
    Route::prefix('keyword-positions')->group(function () {
        Route::match(['get', 'post'], 'report/{keyword_id}', [KeywordSearchController::class, 'report'])->name('keyword-positions.report');
    });

    // Domain routes
    Route::resource('domains', DomainController::class);
    Route::get('domains/report/{id}', [DomainController::class, 'report'])->name('domains.report');

    // Keyword routes
    Route::resource('keywords', KeywordController::class);
    Route::get('keywords/report/{id}', [KeywordController::class, 'report'])->name('keywords.domain');
});

// Auth routes
require __DIR__.'/auth.php';
