<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\File
 *
 * @property int $id
 * @property int $file_type_id
 * @property string $original_path
 * @property string|null $edited_path
 * @property string|null $taken_at
 * @property int|null $geolocation_id
 * @property int $insurance_request_attachment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InsuranceRequestAttachment|null $attachment
 * @property-read \App\Models\Geolocation|null $geolocation
 * @property-read \App\Models\FileType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereEditedPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFileTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereGeolocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereInsuranceRequestAttachmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereOriginalPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereTakenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class File extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = 'files';

    public function type() : BelongsTo
    {
        return $this->belongsTo(FileType::class);
    }

    public function geolocation() : BelongsTo
    {
        return $this->belongsTo(Geolocation::class);
    }

    public function attachment() : BelongsTo
    {
        return $this->belongsTo(InsuranceRequestAttachment::class);
    }

}
