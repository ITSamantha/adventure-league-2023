<?php

namespace App\UseCases\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RegisterUseCase
{
    public function __invoke(RegisterRequest $request): User
    {
        /** @var User $user */
        $user = User::query()->create($request->all());

        $user->addRole(Role::user);

        return $user;
    }
}