<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
 * @property bool $is_nn_validated
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

    /**
     * @param $files
     * @param $data
     *
     * @return Collection<File>
     */
    public static function createFromMany($files, $data): Collection
    {
        $createdFiles = collect();

        foreach ($files as $path) {
//            $extension = pathinfo($path, PATHINFO_EXTENSION);
//            $newPath = Str::random(40) . '.' . $extension;
//
//            Storage::disk('images')->put($newPath, file_get_contents($path));
//            unlink($path);  // delete tmp file from /tmp

            //todo create geolocation, taken_at

            $newFile = self::query()->create($data + [
                'original_path' => $path,
                'edited_path' => null,
                'taken_at' => Carbon::now(),
                'geolocation_id' => 1,
            ]);

            $createdFiles->add($newFile);
        }

        return $createdFiles;
    }
}
