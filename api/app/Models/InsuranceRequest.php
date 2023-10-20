<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 *
 * @property-read InsuranceRequestStatus $status
 *
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
    protected $guarded = [];
    protected $table = 'insurance_requests';

    public function status(): BelongsTo
    {
        return $this->belongsTo(InsuranceRequestStatus::class, 'insurance_request_status_id', 'id');
    }

    public function updateStatus(int $statusId)
    {
        $this->insurance_request_status_id = $statusId;
    }
}
