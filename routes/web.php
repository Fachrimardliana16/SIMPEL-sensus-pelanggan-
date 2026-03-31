<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicDashboardController;
use App\Http\Controllers\ExportController;

Route::get('/', [PublicDashboardController::class, 'index']);
Route::get('/api/dashboard-stats', [PublicDashboardController::class, 'getApiStats']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/s/{slug}', \App\Livewire\TakeSurvey::class)->name('survey.show');

// Export PDF Route with Filters
Route::get('/export/sensus-pdf', [ExportController::class, 'downloadSensusPdf'])
    ->name('export.sensus.pdf')
    ->middleware(['auth']);

Route::get('/export/sensus-print/{id}', [ExportController::class, 'printSingleSensus'])
    ->name('export.sensus.print')
    ->middleware(['auth']);

