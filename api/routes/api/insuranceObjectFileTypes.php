<?php

use App\Http\Controllers\InsuranceObjectFileType\InsuranceObjectFileTypeController;
use Illuminate\Support\Facades\Route;

Route::post('insurance_object_file_types/get', [InsuranceObjectFileTypeController::class, 'getByInsuranceObjectType']);
