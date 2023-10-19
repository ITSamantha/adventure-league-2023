<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $telegram_id
 * @property string $name
 * @property string $surname
 * @property string|null $patronymic
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read string $full_name
 * @property-read Collection<InsuranceRequest> $insuranceRequests
 * @property-read Collection<Role> $roles
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelegramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
class User extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'telegram_id',
    ];

    public function insuranceRequests(): HasMany
    {
        return $this->hasMany(InsuranceRequest::class, 'user_id', 'id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_has_role', 'user_id', 'role_id');
    }

    public function addRole(int $roleId)
    {
        $this->roles()->create([
            'role_id' => $roleId,
        ]);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->surname . ($this->patronymic ? ' ' . $this->patronymic : '');
    }
}
