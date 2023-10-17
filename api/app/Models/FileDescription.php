<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FileDescription
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FileDescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileDescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileDescription query()
 * @mixin \Eloquent
 */
class FileDescription extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'file_descriptions';

}
