<?php

namespace App\UseCases\InsuranceRequestAttachment;

use App\Exceptions\ImageExceptions\DimensionsException;
use App\Http\Requests\InsuranceRequestAttachment\InsuranceRequestAttachmentStoreRequest;
use App\Models\File;
use App\Models\FileType;
use App\Models\InsuranceObjectFileType;
use App\Models\InsuranceRequest;
use App\Models\InsuranceRequestAttachment;
use App\Models\AttachmentStatus;
use App\Services\NeuralNetService;
use App\Services\TelegramService;
use App\Services\TGBotService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InsuranceRequestAttachmentStoreUseCase
{
    const MIN_IMAGE_WIDTH = 1600;
    const MIN_IMAGE_HEIGHT = 1200;

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
                'attachment_status_id' => AttachmentStatus::PENDING,
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

                try {
                    $this->validateFiles($files, $fileTypeId);
                } catch (DimensionsException $e) {
                    foreach ($files as $file) {
                        unlink(Storage::disk('images')->path($file)); // todo with other files
                    }

                    return [
                        'success' => false,
                        'message' => 'Разрешение фото должно быть не менее ' . self::MIN_IMAGE_WIDTH . 'x' . self::MIN_IMAGE_HEIGHT,
                    ];
                }

                $ira->attachment_status_id = AttachmentStatus::REVISION_BY_BOT;

                $fileModels = File::createFromMany($files, [
                    'insurance_request_attachment_id' => $ira->id,
                    'file_type_id' => $fileTypeId,
                ]);

                $ira->items()->saveMany($fileModels);
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

        if ($request->input('is_last')) {
            //validate via imageservice todo geo time

            // send to nn validation
            if ($fileTypeId === FileType::PHOTO) {
                NeuralNetService::sendToValidation($result['data']);
            }
            // ping bot if last
            TGBotService::lol();
        }

        return $result;
    }

    /**
     * @param array $files
     * @param int $fileTypeId
     *
     * @return array
     *
     * @throws DimensionsException
     */
    protected function validateFiles(array $files, int $fileTypeId): array
    {
        $errors = [];

        switch ($fileTypeId) {
            case FileType::PHOTO:
                foreach ($files as $path) {
                    [$width, $height] = getimagesize(Storage::disk('images')->path($path));

                    if ($width < self::MIN_IMAGE_WIDTH || $height < self::MIN_IMAGE_HEIGHT) {
                        throw new DimensionsException();
                    }
                    //todo exif
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