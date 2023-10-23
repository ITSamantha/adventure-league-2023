<?php

namespace App\Services;

use App\Exceptions\ImageExceptions\ImageException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TelegramService
{
    /**
     * @param array $links
     *
     * @return array
     * @throws ImageException
     */
    public function getFiles(array $links): array
    {
        $paths = [];
        foreach ($links as $link) {
            $origFileName = explode('/', $link);
            $extension = explode('.', $origFileName[count($origFileName) - 1])[1];
            $imageExtensions = array("jpg", "jpeg", "png", "webp", "bmp");
            if (!in_array(strtolower($extension), $imageExtensions)) {
                throw new ImageException("Необходимо загрузить фотографию допустимого формата: jpg, jpeg, png, webp, bmp");
            }
            $path = Str::random(40) . '.' . $extension;
            copy($link, Storage::disk('images')->path($path));
            $paths[] = Storage::disk('images')->path($path);
        }
        return $paths;
    }
}