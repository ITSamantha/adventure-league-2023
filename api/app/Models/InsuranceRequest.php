<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\InsuranceRequest
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $insurance_request_status_id
 * @property int $insurance_object_type_id
 * @property string $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest whereInsuranceObjectTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest whereInsuranceRequestStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequest whereUserId($value)
 */
class InsuranceRequest extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'insurance_requests';

}
