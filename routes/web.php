<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/s/{slug}', \App\Livewire\TakeSurvey::class)->name('survey.show');
Route::get('/reports/census-pdf', [App\Http\Controllers\ReportController::class, 'downloadCensusPdf'])->name('reports.census-pdf')->middleware(['auth']);
