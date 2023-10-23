<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InsuranceRequestStatus
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestStatus whereUpdatedAt($value)
 */
class InsuranceRequestStatus extends Model
{
    protected $guarded = [];

    public const APPROVED = 1;
    public const DECLINED = 2;
    public const REVISION = 3;
    public const PENDING_BOT = 4;
    public const PENDING_MANAGER = 5;
    public const CREATED = 6;

    public const default = self::PENDING_BOT;

    protected $table = 'insurance_request_statuses';

}
