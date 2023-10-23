<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\FileType;
use App\Models\InsuranceRequestAttachment;
use App\Models\AttachmentStatus;
use App\Models\InsuranceRequestStatus;
use App\Models\NeuralNetRequest;
use App\Models\NeuralNetRequestStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SendRequestToNeuralNetwork extends Command
{
    public const MAX_MINUTES_PENDING_REQUEST = 15;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send_next_nn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var NeuralNetRequest $pendingRequest */
        $pendingRequest = NeuralNetRequest::query()
            ->where('status_id', NeuralNetRequestStatus::pending)
            ->first();

        $this->log('pending request found');
        /** check if there is a pending request */
        if ($pendingRequest) {
            /** change status to resend request if it is pending more than const */
            if ($pendingRequest->updated_at->diffInMinutes(Carbon::now()) >= self::MAX_MINUTES_PENDING_REQUEST) {
                $pendingRequest->status_id = NeuralNetRequestStatus::created;
                $pendingRequest->save();
                $this->log('pending request timeout status reset');
            /** waiting for request to finish */
            } else {
                $this->log('waiting...');
                return;
            }
        }
        /** @var NeuralNetRequest $firstRequestInQueue */
        $firstRequestInQueue = NeuralNetRequest::query()
            ->where('status_id', NeuralNetRequestStatus::created)
            ->orderBy('created_at', 'ASC')
            ->first();

        if (is_null($firstRequestInQueue)) {
            return;
        }
        $this->log('first in queue got');

        $firstRequestInQueue->status_id = NeuralNetRequestStatus::pending;
        $firstRequestInQueue->save();

        $dataToSend = [];
        /** Get all iras with attachment type of photos */
        /** @var Collection<InsuranceRequestAttachment> $iras */
        $iras = $firstRequestInQueue->insuranceRequest->attachments()
            ->whereHas('ioft', function ($q) {
                $q->where('file_type_id', FileType::PHOTO);
            })->get();

        $this->log('got all iras');
        ini_set('memory_limit', '-1');
        /** @var InsuranceRequestAttachment $ira */
        foreach ($iras as $ira) {
            $nextImages = [];

            /** @var File $image */
            foreach ($ira->items as $image) {
//                $nextImages[$image->id] = base64_encode(Storage::disk('images')->get($image->original_path));
                $nextImages[$image->id] = base64_encode(file_get_contents($image->original_path));
            }

            $dataToSend[$ira->id] = $nextImages;
        }
        $this->log('got data to send');

        $response = Http::post(env('NEURALNET_APP_URL') . '/upload_images', [
            'images' => $dataToSend,
        ]);
        $this->log('got response from nn');

        $data = $response->json();

        $failedIras = [];
        $processedIras = [];

        foreach ($data['iras'] as $iraId => $value) {
            if ($value === 1) {
                $processedIras[] = $iraId;
            } else {
                $failedIras[] = $iraId;
            }
        }

        if (count($failedIras) === 0) {
            $firstRequestInQueue->insuranceRequest->insurance_request_status_id = InsuranceRequestStatus::PENDING_MANAGER;
        } else {
            $firstRequestInQueue->insuranceRequest->insurance_request_status_id = InsuranceRequestStatus::DECLINED;
        }
        $this->log('statuses changed');

        /** Notify client that status of one of his irequests has changed */
        Http::post(env('webhooks_app_url') . '/status_changed', [
            'telegram_id' => $firstRequestInQueue->insuranceRequest->user->telegram_id
        ]);
        $this->log('posted to webhook');

        DB::transaction(function () use ($firstRequestInQueue, $processedIras, $failedIras, $data) {
            $firstRequestInQueue->save();
            $firstRequestInQueue->insuranceRequest->save();

            InsuranceRequestAttachment::query()
                ->whereIn('id', $processedIras)
                ->update([
                    'status_id' => AttachmentStatus::REVISION_BY_MANAGER,
                ]);

            InsuranceRequestAttachment::query()
                ->whereIn('id', $failedIras)
                ->update([
                    'status_id' => AttachmentStatus::DECLINED,
                ]);

            foreach($data['images'] as $imageId => $value) {
                $this->drawWatermark($imageId, $value);

                $this->log('watermark drawn');
            }
        });
        $this->log('saved all');
    }

    protected function log(string $message)
    {
        if (env('APP_DEBUG')) {
            $this->info($message);
        }
    }

    protected function drawWatermark($imageId, $success)
    {
        File::query()->where('id', $imageId)->update([
           'is_nn_validated' => !!$success,
        ]);
//        $image = File::query()->find($imageId);
//        // Load your image
//        $sourceImage = imagecreatefromjpeg(Storage::disk('images')->path($image->original_path)); // Change 'source.jpg' to your image file.
//
//        $watermarkPath = resource_path('/assets/' . ($success ? 'tick' : 'cross') . '.png');
//        // Define the watermark
//        $watermarkImage = imagecreatefrompng($watermarkPath); // Change 'watermark.png' to your watermark image.
//
//        // Set the transparency level for the watermark (0-100)
//        $alpha = 70; // You can adjust this value.
//
//        // Get the dimensions of the source image and watermark
//        $sourceWidth = imagesx($sourceImage);
//        $sourceHeight = imagesy($sourceImage);
//        $watermarkWidth = 30;
//        $watermarkHeight = 30;
//
//        // Calculate the position to place the watermark (e.g., in the bottom right corner)
//        $positionX = $sourceWidth - $watermarkWidth - 10; // Adjust these values as needed
//        $positionY = $sourceHeight - $watermarkHeight - 10;
//
//        // Apply the watermark to the source image
//        imagecopymerge($sourceImage, $watermarkImage, $positionX, $positionY, 0, 0, $watermarkWidth, $watermarkHeight, $alpha);
//
//        // Add text to the image
//        $textColor = imagecolorallocate($sourceImage, 255, 255, 255); // RGB color for the text
//        $text = 'Проверено Совкомбанк Digital Bot. Фото снято: ' . Carbon::parse($image->taken_at)->format("d.m.Y h:i:s");
//        $font = 'arial'; // Path to the font file, e.g., Arial.ttf
//        $fontSize = 15;
//        $textX = 15; // Adjust the text position as needed
//        $textY = 28;
//
//        imagettftext($sourceImage, $fontSize, 0, $textX, $textY, $textColor, $font, $text);
//
//        // Output or save the watermarked image
////        header('Content-type: image/jpeg'); // Change to the appropriate content type (JPEG, PNG, etc.)
//        $filename = Str::random(40);
//        $filename .= '.' . explode('.', $image->original_path)[1];
//
//        $path = Storage::disk('images')->path($filename);
//
//        imagejpeg($sourceImage, $path); // You can save it to a file or display it
//
//        $image->edited_path = $filename;
//        $image->save();
//
//        // Clean up resources
//        imagedestroy($sourceImage);
//        imagedestroy($watermarkImage);
    }
}
