<?php

namespace App\UseCases\InsuranceRequestAttachment;

use App\Exceptions\ImageExceptions\DimensionsException;
use App\Exceptions\ImageExceptions\EXIFException;
use App\Exceptions\ImageExceptions\ImageException;
use App\Http\Requests\InsuranceRequestAttachment\InsuranceRequestAttachmentStoreRequest;
use App\Models\File;
use App\Models\FileType;
use App\Models\InsuranceObjectFileType;
use App\Models\InsuranceRequest;
use App\Models\InsuranceRequestAttachment;
use App\Models\AttachmentStatus;
use App\Services\ImageService;
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
    protected ImageService $imageService;

    public function __construct(TelegramService $telegramService, ImageService $imageService)
    {
        $this->telegramService = $telegramService;
        $this->imageService = $imageService;
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
                    foreach ($files as $file) {
                        $md = $this->imageService->getMediaMetaData($file);

                        $fileModels = File::createFromMany($files, [
                            'insurance_request_attachment_id' => $ira->id,
                            'file_type_id' => $fileTypeId,
                        ], $md);
                    }


                    $ira->attachment_status_id = AttachmentStatus::REVISION_BY_BOT;

//                    $ira->items()->saveMany($fileModels);

                } catch (DimensionsException $e) {
                    foreach ($files as $file) {
                        unlink(Storage::disk('images')->path($file));
                    }
                    return [
                        'success' => false,
                        'message' => 'Разрешение фото должно быть не менее ' . self::MIN_IMAGE_WIDTH . 'x' . self::MIN_IMAGE_HEIGHT,
                    ];
                } catch (EXIFException $exception) {
                    foreach ($files as $file) {
                        unlink(Storage::disk('images')->path($file));
                    }
                    return [
                        'success' => false,
                        'message' => $exception->getMessage(),
                    ];
                } catch (ImageException) {
                    foreach ($files as $file) {
                        $path = Storage::disk('images')->path($file);
                        if (file_exists($path))
                        unlink($path);
                    }
                    return [
                        'success' => false,
                        'message' => 'Ошибки при загрузке изображения. Убедитесь, что вы загрузили изображение допустимого формата (.png, .jpg, .webp)',
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

        if ($request->input('is_last')) {
            //validate via imageservice
            $imageService = new ImageService();
            try {
                $imageService->validateImagesInInsuranceRequest($ir);
            } catch (ImageException $e) {
                return [
                  'success' => false,
                  'message' => 'Фото было сделано в разных местах или в разное время.',
                ];
            }

            // send to nn validation
            if ($fileTypeId === FileType::PHOTO) {
                NeuralNetService::sendToValidation($result['data']);
            }
        }

        return $result;
    }

    /**
     * @param array $files
     * @param int $fileTypeId
     *
     * @return string
     *
     * @throws DimensionsException
     */
    protected function validateFiles(array $files, int $fileTypeId): string
    {
        $errors = [];

        switch ($fileTypeId) {
            case FileType::PHOTO:
                foreach ($files as $path) {
                    [$width, $height] = getimagesize($path);
                    if ($width < self::MIN_IMAGE_WIDTH || $height < self::MIN_IMAGE_HEIGHT) {
                        throw new DimensionsException();
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

        return '';
    }
}