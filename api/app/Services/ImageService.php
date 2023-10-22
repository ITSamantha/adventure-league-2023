<?php

namespace App\Services;

use App\DTO\ImageMetaData;
use App\Exceptions\ImageExceptions\EXIFException;
use App\Exceptions\ImageExceptions\ImageException;
use App\Models\InsuranceRequest;
use Carbon\Carbon;
use Exception;

class ImageService
{

    const ALLOWED_DELTA_DIST_LONGITUDE_LATITUDE = 0.009; // ~ 1km
    const ALLOWED_DELTA_TIME_SECONDS = 3600; // 1hr

    private function gpsToNumber(string $coords): float
    {
        $parts = explode('/', $coords);
        if (count($parts) <= 0) return 0;
        if (count($parts) == 1) return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }

    private function getGps($exifData, $dir): float
    {
        $degrees = count($exifData) > 0 ? $this->gpsToNumber($exifData[0]) : 0;
        $minutes = count($exifData) > 1 ? $this->gpsToNumber($exifData[1]) : 0;
        $seconds = count($exifData) > 2 ? $this->gpsToNumber($exifData[2]) : 0;
        $flip = ($dir == 'W' or $dir == 'S') ? -1 : 1;
        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }


    /**
     * Validate that all the media was taken in approximately same moment
     * and in the same place.
     * @param InsuranceRequest $insuranceRequest
     * @return bool
     * @throws ImageException
     */
    public function validateImagesInInsuranceRequest(InsuranceRequest $insuranceRequest): bool
    {
        $notEditableAttachments = $insuranceRequest->notEditableAttachmentsWithFiles;
        $files = $notEditableAttachments->pluck('items')->collapse();
        // todo: do validation when database will be ready for testing.
        return true;
    }

    /**
     * Accept global path to image and validate it. \
     * If exception wasn't thrown, image is valid; \
     * Storage::drive("public")->path("example.png")
     * @param string $globalPath
     * @return ImageMetaData
     * @throws ImageException
     */
    public function getMediaMetaData(string $globalPath): ImageMetaData
    {
        if (!file_exists($globalPath)) {
            throw new ImageException("Image not found in $globalPath");
        }
        try {
            $exifData = exif_read_data($globalPath);
            $longitude = $this->getGps($exifData["GPSLongitude"], $exifData['GPSLongitudeRef']);
            $latitude = $this->getGps($exifData["GPSLatitude"], $exifData['GPSLatitudeRef']);
            $photoWasTakenAt = Carbon::parse($exifData["DateTimeOriginal"]);
            $photoWasEditedAt = Carbon::parse($exifData["DateTime"]);
        } catch (Exception) {
            throw new EXIFException("Для данного изображения не включён сбор мета-информации (данные снимка) или он настроен некорректно.");
        }
        if (!empty($longitude) && !empty($latitude) && !empty($photoWasEditedAt) && !empty($photoWasTakenAt)) {
            if (abs($photoWasTakenAt->diffInSeconds($photoWasEditedAt)) > 1) {
                throw new EXIFException("Данный снимок был отредактирован перед загрузкой в приложение. Убедитесь, что Вы загружаете его напрямую из галереи.");
            }
            return new ImageMetaData($photoWasTakenAt, $longitude, $latitude);
        } else {
            throw new EXIFException("Не удалось получить информацию о месторасположении и времени снимка. Проверьте настройки устройства, пожалуйста.");
        }

    }

}
