<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class NeuralNetRequestStatus extends Model
{
    protected $fillable = [
        'id', 'name',
    ];

    public const created = 1;
    public const pending = 2;
    public const finished = 3;
}
