<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\KeywordPositionController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('api/keyword-positions', [\App\Http\Controllers\KeywordPositionController::class, 'json'])->name("keyword-positions.json");
    Route::get('api/keywords', [\App\Http\Controllers\KeywordController::class, 'json']);
    Route::post('api/keyword-positions/search', [\App\Http\Controllers\KeywordPositionController::class,'search']);

    Route::resource('keyword-positions', KeywordPositionController::class);

});

Route::get('domains/report/{id}', [\App\Http\Controllers\DomainController::class, 'report'])->middleware(['auth'])->name('domains.report');
Route::resource('domains', \App\Http\Controllers\DomainController::class)->middleware(['auth']);

Route::get('keywords/report/{id}', [\App\Http\Controllers\KeywordController::class, 'report'])->middleware(['auth'])->name('keywords.domain');
Route::resource('keywords', \App\Http\Controllers\KeywordController::class)->middleware(['auth']);

 
require __DIR__.'/auth.php';
