<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AttachmentStatus
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AttachmentStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttachmentStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttachmentStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttachmentStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttachmentStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttachmentStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttachmentStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AttachmentStatus extends Model
{

    use HasFactory;
    protected $guarded = [];
    protected $table = 'attachment_statuses';

    public const APPROVED = 1;
    public const DECLINED = 2;
    public const REVISION_BY_BOT = 3;
    public const REVISION_BY_MANAGER = 4;
    public const PENDING = 5;

    public const default = self::PENDING;

}
