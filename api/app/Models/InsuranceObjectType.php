<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Models\InsuranceObjectType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read Collection<InsuranceObjectFileType> $fileTypes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectType query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectType whereUpdatedAt($value)
 */
class InsuranceObjectType extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'insurance_object_types';

    public const COUNTRY_HOUSE = 1;
    public const CAR = 2;

    public function fileTypes(): HasMany
    {
        return $this->hasMany(InsuranceObjectFileType::class, 'insurance_object_type_id', 'id');
    }
}
