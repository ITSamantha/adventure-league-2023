<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class Role extends Model
{
    public const admin = 1;
    public const moderator = 2;
    public const user = 3;
}
