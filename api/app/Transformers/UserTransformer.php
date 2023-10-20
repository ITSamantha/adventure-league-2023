<?php

namespace App\Transformers;

use App\Models\Role;
use App\Models\User;

class UserTransformer extends AbstractTransformer
{
    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'telegram_id' => $user->telegram_id,
            'name' => $user->full_name,
            'roles' => $user->roles->transform(function (Role $role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }),
        ];
    }
}