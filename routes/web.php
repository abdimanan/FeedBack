<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public feedback routes (no authentication required) - Must be before resource routes
Route::get('/feedback/{token}', [App\Http\Controllers\PublicFeedbackController::class, 'show'])->name('feedback.form');
Route::post('/feedback/{token}', [App\Http\Controllers\PublicFeedbackController::class, 'store'])->name('feedback.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::resource('projects', App\Http\Controllers\ProjectController::class);
    Route::post('projects/{project}/generate-feedback-link', [App\Http\Controllers\ProjectController::class, 'generateFeedbackLink'])->name('projects.generate-feedback-link');
    // Exclude 'show' from resource to avoid conflict with public feedback route
    Route::resource('feedback', App\Http\Controllers\FeedbackController::class)->except(['show']);
    Route::get('feedback/{feedback}/view', [App\Http\Controllers\FeedbackController::class, 'show'])->name('feedback.show');
});

require __DIR__.'/auth.php';
