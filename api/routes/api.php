<?php

use App\Http\Controllers\ClientImageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'api_token'])->group(function () {

    require base_path('routes/api/auth.php');
    require base_path('routes/api/insuranceRequests.php');
    require base_path('routes/api/insuranceObjectFileTypes.php');
    require base_path('routes/api/insuranceObjects.php');
    require base_path('routes/api/insuranceRequestAttachments.php');

    Route::post('/test-image', [ClientImageController::class, 'testImage']);
//    Route::any('{any}', [NotFoundController::class, 'notFound'])->where('any', '.*');
});
