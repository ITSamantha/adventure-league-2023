<?php

namespace Database\Seeders;

use App\Models\AttachmentStatus;
use App\Models\InsuranceRequestStatus;
use App\Models\Role;

class PermissionsSeeder extends GenericSeeder
{
    protected array $data = [
        Role::class => [
            Role::admin => ['name' => 'Администратор'],
            Role::moderator => ['name' => 'Модератор'],
            Role::user => ['name' => 'Пользователь'],
        ]
    ];
}
