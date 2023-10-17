<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FileType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FileType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileType query()
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FileType extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = 'file_types';

    public const PHOTO = 1;
    public const VIDEO = 2;
    public const DOCUMENT = 3;

}
