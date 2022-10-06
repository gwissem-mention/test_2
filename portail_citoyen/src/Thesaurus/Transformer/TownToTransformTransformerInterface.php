<?php

declare(strict_types=1);

namespace App\Thesaurus\Transformer;

interface TownToTransformTransformerInterface
{
    /**
     * @param array<mixed> $towns
     *
     * @return array<string, string>
     */
    public function transform(array $towns): array;
}
