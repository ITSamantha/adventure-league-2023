<?php

namespace App\Services;

use App\Models\File;
use App\Models\FileType;
use App\Models\InsuranceRequestAttachment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class NeuralNetService
{
    public static function sendToValidation(InsuranceRequestAttachment $ira)
    {
        $encodedImages = [];

        /** @var File $item */
        foreach ($ira->items as $item) {
            if ($item->file_type_id !== FileType::PHOTO) {
                throw new \Exception('lalalaal');
            }

            $encodedImage = Storage::get($item->original_path);

            $encodedImages[] = base64_encode($encodedImage);
        }

        Http::post(env('NEURALNET_APP_URL' . '/uri'), [
            'ira_id' => $ira->id,
            'webhook' => route('nn_webhook'),
            'images' => $encodedImages,
        ]);
    }
}