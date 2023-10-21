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
            $path = Str::random(40) . '.jpg'; // todo ext

            copy($link, Storage::disk('images')->path($path));

            $paths[] = $path;
        }

        return $paths;
    }
}