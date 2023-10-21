<?php

namespace Database\Seeders;

use App\Models\AttachmentStatus;
use App\Models\InsuranceRequestAttachmentStatus;
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
            InsuranceRequestStatus::APPROVED => ['name' => 'Одобрено'],
            InsuranceRequestStatus::DECLINED => ['name' => 'Отклонено'],
            InsuranceRequestStatus::REVISION => ['name' => 'Нуждается в доработке'],
            InsuranceRequestStatus::PENDING => ['name' => 'На рассмотрении'],
        ],
        InsuranceRequestAttachmentStatus::class => [
            InsuranceRequestAttachmentStatus::APPROVED => ['name' => 'Одобрено'],
            InsuranceRequestAttachmentStatus::DECLINED => ['name' => 'Отклонено'],
            InsuranceRequestAttachmentStatus::REVISION => ['name' => 'Нуждается в доработке'],
            InsuranceRequestAttachmentStatus::PENDING => ['name' => 'На рассмотрении'],
        ],
        NeuralNetRequestStatus::class => [

        ],
    ];
}
