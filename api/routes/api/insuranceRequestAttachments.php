<?php

use App\Http\Controllers\InsuranceRequestAttachment\InsuranceRequestAttachmentController;
use Illuminate\Support\Facades\Route;

Route::post('/ira', [InsuranceRequestAttachmentController::class, 'store']);
Route::post('/ira/neural_net_validated', [InsuranceRequestAttachmentController::class, 'neural_net_validated'])->name('nn_webhook');