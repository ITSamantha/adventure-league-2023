<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InsuranceObjectFileType
 *
 * @property int $id
 * @property int $file_type_id
 * @property int $file_description_id
 * @property int $insurance_object_type_id
 * @property int $min_photo_count
 * @property int $is_editable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType query()
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereFileDescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereFileTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereInsuranceObjectTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereIsEditable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereMinPhotoCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InsuranceObjectFileType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InsuranceObjectFileType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'insurance_object_file_types';

}
