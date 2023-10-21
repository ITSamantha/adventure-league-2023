<?php

namespace App\UseCases\InsuranceRequestAttachment;

use App\Http\Requests\InsuranceRequestAttachment\InsuranceRequestAttachmentStoreRequest;
use App\Models\File;
use App\Models\FileType;
use App\Models\InsuranceObjectFileType;
use App\Models\InsuranceRequest;
use App\Models\InsuranceRequestAttachment;
use App\Models\InsuranceRequestAttachmentStatus;
use App\Services\NeuralNetService;
use App\Services\TelegramService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InsuranceRequestAttachmentStoreUseCase
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function __invoke(InsuranceRequestAttachmentStoreRequest $request)
    {
        $user = $request->getRequestUser();

        /** @var InsuranceRequest $ir */
        $ir = InsuranceRequest::query()->find($request->input('insurance_request_id'));
        /** @var InsuranceObjectFileType $ioft */
        $ioft = InsuranceObjectFileType::query()->find($request->input('insurance_object_file_type'));

        $fileTypeId = $ioft->file_type_id;

        $result = DB::transaction(function () use ($request, $ir, $ioft, $fileTypeId) {
            $ira = InsuranceRequestAttachment::query()->create([
                'ioft_id' => $ioft->id,
                'insurance_request_id' => $ir->id,
                'attachment_status_id' => InsuranceRequestAttachmentStatus::PENDING,
            ]);

            //  if has files download, validate and save
            if ($request->has('links')) {
                $links = $request->input('links');

                if (count($links) < $ioft->min_photo_count) {
                    return [
                        'success' => false,
                        'message' => 'Должно быть как минимум ' . $ioft->min_photo_count . ' файлов',
                    ];
                }

                $files = $this->telegramService->getFiles($links);

                $errors = $this->validateFiles($files, $fileTypeId);

                if (count($errors) === 0) {
                    $ira->attachment_status_id = InsuranceRequestAttachmentStatus::REVISION;

                    $fileModels = File::createFromMany($files, [
                        'insurance_request_attachment_id' => $ira->id,
                        'file_type_id' => $fileTypeId,
                    ]);

                    $ira->items()->saveMany($fileModels);
                } else {
                    foreach ($files as $file) {
                        unlink(Storage::disk('images')->path($file)); // todo with other files
                    }

                    return [
                        'success' => false,
                        'message' => 'Файлы не соответствуют требованиям',
                        'errors' => $errors,
                    ];
                }
            } else {
                $ira->text = $request->input('text');
            }

            $ira->save();

            return [
                'success' => true,
                'data' => $ira,
            ];
        });

        if (!$result['success']) {
            return $result;
        }

        if ($fileTypeId === FileType::PHOTO) {
//            NeuralNetService::sendToValidation($result['data']);
        }

        if ($request->input('is_last')) {
            // ping bot if last
        }

        return $result;
    }

    protected function validateFiles(array $files, int $fileTypeId): array
    {
        $errors = [];

        switch ($fileTypeId) {
            case FileType::PHOTO:
                foreach ($files as $path) {
                    [$width, $height] = getimagesize(Storage::disk('images')->path($path));
                    // todo exif stuff
                    // todo if width < config width ...
                    if (false) {
                        $errors[] = 'ploho...';
                    }
                }
                break;
            case FileType::VIDEO:
                // validate video
                break;
            case FileType::DOCUMENT:
                // validate document
                break;
        }

        return $errors;
    }
}