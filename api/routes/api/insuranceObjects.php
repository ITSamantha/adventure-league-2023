<?php

use App\Http\Controllers\InsuranceObjectType\InsuranceObjectTypeController;
use Illuminate\Support\Facades\Route;

Route::get('insurance_objects', [InsuranceObjectTypeController::class, 'index']);