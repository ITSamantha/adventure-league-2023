<?php

namespace App\Http\Controllers;

use App\Exceptions\ImageExceptions\EXIFException;
use App\Exceptions\ImageExceptions\ImageException;
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
            return response()->json(['success' => false, 'message' => 'Не можем обработать этот файл. Попробуйте, пожалуйста, другой формат.']);
        }
        $photos = $telegramService->getFiles([$link]);
        try {
            $imageService->getMediaMetaData($photos[0]);
            return response()->json(['success' => true]);
        } catch (EXIFException $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        } catch (ImageException) {
            return response()->json(['success' => false, 'message' => 'Не можем обработать этот файл. Попробуйте, пожалуйста, другой формат.']);
        }
    }

}
