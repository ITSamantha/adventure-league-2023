<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $status_id
 *
 * @property-read NeuralNetRequestStatus $status
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
}
