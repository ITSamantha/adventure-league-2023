<?php

namespace App\Transformers;

use App\Models\InsuranceObjectFileType;

class InsuranceObjectFileTypeTransformer
{
    public function transform(InsuranceObjectFileType $ioft): array
    {
        return [
            'id' => $ioft->id,
            'file_type_id' => $ioft->file_type_id,
            'file_type' => $ioft->fileType->name,
            'file_description_id' => $ioft->file_description_id,
            'file_description' => $ioft->fileDescription->content,
            'min_photo_count' => $ioft->min_photo_count,
            'id_editable' => $ioft->is_editable,
        ];
    }
}