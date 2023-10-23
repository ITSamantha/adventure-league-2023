<?php

namespace App\DTO;

use Carbon\Carbon;

class ImageMetaData
{

    public Carbon $taken_at;
    public float $longitude;
    public float $latitude;

    public function __construct(Carbon $takenAt, float $longitude, float $latitude)
    {
        $this->taken_at = $takenAt;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

}