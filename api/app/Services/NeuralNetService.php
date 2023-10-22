<?php

namespace App\Services;

use App\Models\InsuranceRequestAttachment;
use App\Models\NeuralNetRequest;
use App\Models\NeuralNetRequestStatus;

class NeuralNetService
{
    public static function sendToValidation(InsuranceRequestAttachment $ira)
    {
        $nnRequest = NeuralNetRequest::query()->create([
            'status_id' => NeuralNetRequestStatus::created,
            'ir_id' => $ira->insuranceRequest->id,
        ]);
    }
}