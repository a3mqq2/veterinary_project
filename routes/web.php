<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\AnimalCaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;


Route::redirect('/', '/home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::middleware('auth')->group(function () {
    Route::get('/home', [AuthController::class, 'home'])->name('home');

    // Cases CRUD routes (available for all authenticated users)
    Route::resource('cases', AnimalCaseController::class);
    Route::patch('cases/{case}/update-status', [AnimalCaseController::class, 'updateStatus'])->name('cases.update-status');
    Route::get('cases-export', [AnimalCaseController::class, 'export'])->name('cases.export');
    Route::post('cases-import', [AnimalCaseController::class, 'import'])->name('cases.import');
    Route::get('cases-template', [AnimalCaseController::class, 'downloadTemplate'])->name('cases.template');
    Route::get('cases-print', [AnimalCaseController::class, 'printList'])->name('cases.print');
    Route::get('cases/{case}/print', [AnimalCaseController::class, 'printShow'])->name('cases.print-show');

    // Profile routes
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Region API routes for Select2 (available for all authenticated users)
    Route::get('api/regions/search', [RegionController::class, 'search'])->name('api.regions.search');
    Route::post('api/regions/quick-store', [RegionController::class, 'quickStore'])->name('api.regions.quick-store');

    // Admin only routes
    Route::middleware('admin')->group(function () {
        // Symptoms CRUD routes (Admin only)
        Route::resource('symptoms', SymptomController::class);
        Route::patch('symptoms/{symptom}/toggle-status', [SymptomController::class, 'toggleStatus'])->name('symptoms.toggle-status');

        // Diseases CRUD routes (Admin only)
        Route::resource('diseases', DiseaseController::class);
        Route::patch('diseases/{disease}/toggle-status', [DiseaseController::class, 'toggleStatus'])->name('diseases.toggle-status');

        // Users CRUD routes (Admin only)
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Regions CRUD routes (Admin only)
        Route::resource('regions', RegionController::class);
        Route::patch('regions/{region}/toggle-status', [RegionController::class, 'toggleStatus'])->name('regions.toggle-status');
    });
});