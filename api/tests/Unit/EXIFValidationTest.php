<?php

namespace Tests\Unit;

use App\Exceptions\ImageExceptions\ImageException;
use App\Services\ImageService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EXIFValidationTest extends TestCase
{

    protected ImageService $service;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = app()->make(ImageService::class);
    }

    public function test_bad_example(): void
    {
        try {
            $photoMetaData = $this->service->validateEXIF(Storage::drive("public")->path("st.jpg"));
            $this->fail();
        } catch (ImageException) {
            $this->assertTrue(true);
        }
    }

    public function test_good_example(): void
    {
        try {
            $photoMetaData = $this->service->validateEXIF(Storage::drive("public")->path("IMG_20231014_163632.jpg"));
            $this->assertTrue(true);
        } catch (ImageException) {
            $this->fail();
        }
    }

    public function test_good_example1(): void
    {
        try {
            $photoMetaData = $this->service->validateEXIF(Storage::drive("public")->path("IMG_20231014_165010.jpg"));
            $this->assertTrue(true);
        } catch (ImageException) {
            $this->fail();
        }
    }

    public function test_good_example2(): void
    {
        try {
            $photoMetaData = $this->service->validateEXIF(Storage::drive("public")->path("IMG_20231014_161530.jpg"));
            $this->assertTrue(true);
        } catch (ImageException) {
            $this->fail();
        }
    }

}
