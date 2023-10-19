<?php

namespace App\Facades;

use App\Transformers\AbstractTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Transformer
{
    /**
     * @param Collection|LengthAwarePaginator|Model|null $data
     * @param class-string<AbstractTransformer> $transformer
     *
     * @return array|null
     */
    public static function transform(Model|LengthAwarePaginator|Collection|null $data, string $transformer): array|null
    {
        $transformer = new $transformer;

        if (is_null($data)) {
            $transformedData = null;
        }
        else if ($data instanceof Model) {
            $transformedData = $transformer->transform($data);
        } else {
            $transformedData = $data->map(function ($item) use ($transformer) {
                return $transformer->transform($item);
            })->toArray();
        }

        return $transformedData;
    }
}