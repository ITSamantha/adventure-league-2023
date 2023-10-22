<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TelegramService
{
    /**
     * @param array $links
     *
     * @return array
     */
    public function getFiles(array $links): array
    {
        $paths = [];

        foreach ($links as $link) {
            $origFileName = explode('/', $link);
            $extension = explode('.', $origFileName[count($origFileName) - 1])[1];

            $path = Str::random(40) . '.' . $extension;
            //todo
            $link = 'C:\Users\tyumi\Desktop\cat.jpg';
            copy($link, Storage::disk('images')->path($path));

            $paths[] = $path;
        }

        return $paths;
    }
}