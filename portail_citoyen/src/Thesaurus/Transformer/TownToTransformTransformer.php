<?php

declare(strict_types=1);

namespace App\Thesaurus\Transformer;

use App\Form\DataTransformer\TownToTownAndDepartmentTransformer;

class TownToTransformTransformer implements TownToTransformTransformerInterface
{
    public function __construct(private readonly TownToTownAndDepartmentTransformer $townToTownAndDepartmentTransformer)
    {
    }

    public function transform(array $towns): array
    {
        $townsTransformed = [];

        /**
         * @var array<string, int> $town
         */
        foreach ($towns as $key => $town) {
            $transformedValue = $this->townToTownAndDepartmentTransformer->transform([$key, $town['pel.department']]);
            $townsTransformed[$transformedValue] = $transformedValue;
        }

        return $townsTransformed;
    }
}
