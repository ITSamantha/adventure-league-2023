<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    protected User|null $user;

    private bool $triedToFindUser = false;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Get user by telegram_id passed in header
     *
     * @return User|null
     */
    public function getRequestUser(): User|null
    {
        if ($this->triedToFindUser) {
            return $this->user;
        }

        $telegramId = $this->header('Telegram-Id');

        $user = User::query()->where('telegram_id', $telegramId)->first();

        $this->user = $user;

        return $user;
    }
}