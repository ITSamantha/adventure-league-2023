<?php

namespace App\UseCases\InsuranceRequest;

use App\Http\Requests\InsuranceRequest\InsuranceRequestIndexRequest;
use App\Models\InsuranceRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InsuranceRequestIndexUseCase
{
    /**
     * @param InsuranceRequestIndexRequest $request
     *
     * @return LengthAwarePaginator<InsuranceRequest>
     */
    public function __invoke(InsuranceRequestIndexRequest $request): LengthAwarePaginator
    {
        $user = $request->getrequestUser();

        $insuranceRequests = $user
            ->insuranceRequests()
            ->paginate(5, ['*'], 'page', $request->input('page'));

        return $insuranceRequests;
    }
}