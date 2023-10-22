<?php

namespace Database\Seeders;

use App\Models\AttachmentStatus;
use App\Models\InsuranceRequestStatus;
use App\Models\NeuralNetRequestStatus;

class StatusesSeeder extends GenericSeeder
{
    protected array $data = [
        AttachmentStatus::class => [
            AttachmentStatus::APPROVED => ['name' => 'Одобрено'],
            AttachmentStatus::DECLINED => ['name' => 'Отклонено'],
            AttachmentStatus::REVISION_BY_BOT => ['name' => 'Нуждается в доработке'],
            AttachmentStatus::REVISION_BY_MANAGER => ['name' => 'Нуждается в доработке'],
            AttachmentStatus::PENDING => ['name' => 'На рассмотрении'],
        ],
        InsuranceRequestStatus::class => [
            InsuranceRequestStatus::CREATED => ['name' => 'На заполнении'],
            InsuranceRequestStatus::APPROVED => ['name' => 'Одобрено'],
            InsuranceRequestStatus::DECLINED => ['name' => 'Отклонено'],
            InsuranceRequestStatus::REVISION => ['name' => 'Нуждается в доработке'],
            InsuranceRequestStatus::PENDING_BOT => ['name' => 'Валидация'],
            InsuranceRequestStatus::PENDING_MANAGER => ['name' => 'На рассмотрении'],
        ],
        NeuralNetRequestStatus::class => [
            NeuralNetRequestStatus::created => ['name' => 'Запрос создан'],
            NeuralNetRequestStatus::pending => ['name' => 'Запрос в обработке'],
            NeuralNetRequestStatus::finished => ['name' => 'Запрос выполнен'],
        ],
    ];
}
