<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FileDescription
 *
 * @property int $id
 * @property string $content
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FileDescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileDescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileDescription query()
 */
class FileDescription extends Model
{
    protected $guarded = [];
    protected $table = 'file_descriptions';

}
