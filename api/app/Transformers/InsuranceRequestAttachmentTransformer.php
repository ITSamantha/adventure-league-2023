<?php

namespace App\Transformers;

use App\Models\InsuranceRequestAttachment;

class InsuranceRequestAttachmentTransformer extends AbstractTransformer
{
    public function transform(InsuranceRequestAttachment $ira): array
    {
        return [
            'id' => $ira->id,
        ];
    }
}