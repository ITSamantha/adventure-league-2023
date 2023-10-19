<?php

namespace App\UseCases\InsuranceRequest;

use App\Http\Requests\InsuranceRequest\InsuranceRequestShowRequest;
use App\Models\InsuranceRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InsuranceRequestShowUseCase
{
    /**
     * @param InsuranceRequestShowRequest $request
     *
     * @return InsuranceRequest
     */
    public function __invoke(InsuranceRequestShowRequest $request): InsuranceRequest
    {
        $user = $request->getRequestUser();

        /** @var InsuranceRequest $iRequest */
        $iRequest = $user->insuranceRequests()->find($request->route()->parameter('id'));

        if (is_null($iRequest)) {
            throw new NotFoundHttpException();
        }

        return $iRequest;
    }
}