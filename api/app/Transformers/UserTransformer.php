<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends AbstractTransformer
{
    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'telegram_id' => $user->telegram_id,
            'name' => $user->full_name, // todo role
        ];
    }
}