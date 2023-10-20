<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceRequestAttachmentStatus extends Model
{
    protected $table = 'attachment_statuses';

    public const APPROVED = 1;
    public const DECLINED = 2;
    public const REVISION = 4;
    public const PENDING = 5;
}
