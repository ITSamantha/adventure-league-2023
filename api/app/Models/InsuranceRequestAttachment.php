<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Models\InsuranceRequestAttachment
 *
 * @property int $id
 * @property int $insurance_request_id
 * @property int $ioft_id
 * @property int $attachment_status_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read Collection<File> $items
 * @property-read InsuranceRequest $insuranceRequest
 *
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereAttachmentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereInsuranceRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereIoftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereUpdatedAt($value)
 */
class InsuranceRequestAttachment extends Model
{
    protected $guarded = [];
    protected $table = 'insurance_request_attachments';

    public function items(): HasMany
    {
        return $this->hasMany(File::class, 'insurance_request_attachment_id', 'id');
    }

    public function ioft(): BelongsTo
    {
        return $this->belongsTo(InsuranceObjectFileType::class);
    }

    public function insuranceRequest(): BelongsTo
    {
        return $this->belongsTo(InsuranceRequest::class, 'insurance_request_id', 'id');
    }
}
