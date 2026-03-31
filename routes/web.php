<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicDashboardController;
use App\Http\Controllers\ExportController;

Route::get('/', [PublicDashboardController::class, 'index']);
Route::get('/api/dashboard-stats', [PublicDashboardController::class, 'getApiStats'])
    ->middleware('throttle:60,1');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/s/{slug}', \App\Livewire\TakeSurvey::class)->name('survey.show');

// Export PDF Route with Filters — only Admin/Analyst
Route::get('/export/sensus-pdf', [ExportController::class, 'downloadSensusPdf'])
    ->name('export.sensus.pdf')
    ->middleware(['auth', 'role:Super Admin|Admin|Analyst']);

Route::get('/export/sensus-print/{id}', [ExportController::class, 'printSingleSensus'])
    ->name('export.sensus.print')
    ->middleware(['auth']);

