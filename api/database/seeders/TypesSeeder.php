<?php

namespace Database\Seeders;

use App\Models\AttachmentStatus;
use App\Models\FileType;
use App\Models\InsuranceObjectFileType;
use App\Models\InsuranceObjectType;
use App\Models\InsuranceRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypesSeeder extends GenericSeeder
{

    protected array $data = [
        FileType::class => [
            FileType::PHOTO => ['name' => 'Фото'],
            FileType::VIDEO => ['name' => 'Видео'],
            FileType::DOCUMENT => ['name' => 'Документ'],
            FileType::TEXT => ['name' => 'Текст'],
        ],
        InsuranceObjectType::class => [
            InsuranceObjectType::COUNTRY_HOUSE => ['name' => 'Загородный дом'],
            InsuranceObjectType::CAR => ['name' => 'Транспортное средство'],
        ],
    ];

}
