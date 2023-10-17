<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InsuranceRequestAttachment
 *
 * @property int $id
 * @property int $insurance_request_id
 * @property int $ioft_id
 * @property int $attachment_status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereAttachmentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereInsuranceRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereIoftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceRequestAttachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InsuranceRequestAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'insurance_request_attachments';

}
