<?php

namespace App\UseCases\Auth;

use App\Http\Requests\ApiRequest;
use App\Models\User;

class GetUserUseCase
{
    public function __invoke(ApiRequest $request): User|null
    {
        return $request->getRequestUser();
    }
}