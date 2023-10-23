<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Geolocation
 *
 * @property-read \App\Models\File|null $file
 * @method static \Illuminate\Database\Eloquent\Builder|Geolocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Geolocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Geolocation query()
 * @mixin \Eloquent
 */
class Geolocation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'geolocations';

    public function file() : HasOne
    {
        return $this->hasOne(File::class);
    }

}
