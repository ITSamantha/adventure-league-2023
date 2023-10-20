<?php

namespace App\UseCases\InsuranceObjectFileType;

use App\Http\Requests\InsuranceObjectFileType\InsuranceObjectFileTypeShowRequest;
use App\Models\InsuranceObjectFileType;
use App\Models\InsuranceObjectType;
use Illuminate\Support\Collection;

class InsuranceObjectFileTypeShowUseCase
{
    /**
     * @param InsuranceObjectFileTypeShowRequest $request
     *
     * @return Collection<InsuranceObjectFileType>
     */
    public function __invoke(InsuranceObjectFileTypeShowRequest $request): Collection
    {
        /** @var InsuranceObjectType $insuranceObject */
        $insuranceObject = InsuranceObjectType::query()->find($request->input('insurance_object_id'));

        return $insuranceObject->fileTypes;
    }
}