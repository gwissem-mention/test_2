<?php

declare(strict_types=1);

namespace App\Generator;

interface GeneratorInterface
{
    public function generate(int $index): mixed;
}
