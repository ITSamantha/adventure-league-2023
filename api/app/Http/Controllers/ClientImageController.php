<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use App\Services\TelegramService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientImageController extends Controller
{

    public function testImage(Request $request, TelegramService $telegramService, ImageService $imageService) : JsonResponse
    {
        $link = $request->link;
        if (!$link) {
            return response()->json([], 422);
        }
        $photos = $telegramService->getFiles([$link]);
        try {
            $imageService->getMediaMetaData($photos[0]);
            return response()->json(['success' => true]);
        } catch (Exception) {
            return response()->json([], 422);
        }
    }

}
