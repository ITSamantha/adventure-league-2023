<?php

namespace App\UseCases\InsuranceRequest;

use App\Http\Requests\InsuranceRequest\InsuranceRequestIndexRequest;
use App\Models\InsuranceRequest;
use Illuminate\Support\Collection;

class InsuranceRequestIndexUseCase
{
    /**
     * @param InsuranceRequestIndexRequest $request
     *
     * @return Collection<InsuranceRequest>
     */
    public function __invoke(InsuranceRequestIndexRequest $request): Collection
    {
        $user = $request->getrequestUser();

        $insuranceRequests = $user->insuranceRequests;

        return $insuranceRequests;
    }
}