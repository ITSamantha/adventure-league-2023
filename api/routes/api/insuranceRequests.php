<?php

use App\Http\Controllers\InsuranceRequest\InsuranceRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/insurance_requests', [InsuranceRequestController::class, 'index']);
Route::get('/insurance_requests/{id}', [InsuranceRequestController::class, 'show']);
Route::post('/insurance_requests', [InsuranceRequestController::class, 'save']);