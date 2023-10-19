<?php

namespace App\Transformers;

use App\Models\InsuranceObjectType;

class InsuranceObjectTypeTransformer extends AbstractTransformer
{
    public function transform(InsuranceObjectType $iOType): array
    {
        return [
            'id' => $iOType->id,
            'name' => $iOType->name,
        ];
    }
}