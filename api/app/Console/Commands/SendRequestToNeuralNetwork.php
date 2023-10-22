<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\FileType;
use App\Models\InsuranceRequestAttachment;
use App\Models\NeuralNetRequest;
use App\Models\NeuralNetRequestStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        /** check if there is a pending request */
        if ($pendingRequest) {
            /** change status to resend request if it is pending more than const */
            if ($pendingRequest->updated_at->diffInMinutes(Carbon::now()) >= self::MAX_MINUTES_PENDING_REQUEST) {
                $pendingRequest->status_id = NeuralNetRequestStatus::created;
                $pendingRequest->save();
            /** waiting for request to finish */
            } else {
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

        $firstRequestInQueue->status_id = NeuralNetRequestStatus::pending;

        $dataToSend = [];
        /** Get all iras with attachment type of photos */
        /** @var Collection<InsuranceRequestAttachment> $iras */
        $iras = $firstRequestInQueue->insuranceRequest->attachments()
            ->whereHas('ioft', function ($q) {
                $q->where('file_type_id', FileType::PHOTO);
            })->get();
//        dd(base64_encode(Storage::disk('images')->get($iras->last()->items->first()->original_path)));
//        dd($iras->last()->items->first()->original_path);
        /** @var InsuranceRequestAttachment $ira */
        foreach ($iras as $ira) {
            $nextImages = [];

            /** @var File $image */
            foreach ($ira->items as $image) {
//                $nextImages[$image->id] = Storage::disk('images')->path($image->original_path);
                $nextImages[$image->id] = base64_encode(Storage::disk('images')->get($image->original_path));
            }

            $dataToSend[$ira->id] = $nextImages;
        }
        dd($dataToSend);
        $response = Http::post(env('NEURALNET_APP_URL' . '/uri'), [
            'ira_id' => $ira->id,
            'webhook' => route('nn_webhook'),
            'images' => $dataToSend,
        ]);


    }
}
