<?php

namespace App\Facades;

use App\Transformers\AbstractTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Transformer
{
    /**
     * @param Collection|LengthAwarePaginator|Model $data
     * @param class-string<AbstractTransformer> $transformer
     *
     * @return array
     */
    public static function transform(Model|LengthAwarePaginator|Collection $data, string $transformer): array
    {
        $transformer = new $transformer;

        if ($data instanceof Model) {
            $transformedData = $transformer->transform($data);
        } else {
            $transformedData = $data->map(function ($item) use ($transformer) {
                return $transformer->transform($item);
            })->toArray();
        }

        return $transformedData;
    }
}