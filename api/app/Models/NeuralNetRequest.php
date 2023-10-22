<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $status_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read NeuralNetRequestStatus $status
 * @property-read InsuranceRequest $insuranceRequest
 */
class NeuralNetRequest extends Model
{
    protected $fillable = [
        'status_id',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(NeuralNetRequestStatus::class, 'status_id', 'id');
    }

    public function insuranceRequest(): BelongsTo
    {
        return $this->belongsTo(InsuranceRequest::class, 'ir_id', 'id');
    }
}
