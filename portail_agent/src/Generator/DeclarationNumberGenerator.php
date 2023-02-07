<?php

declare(strict_types=1);

namespace App\Generator;

class DeclarationNumberGenerator implements GeneratorInterface
{
    public function generate(int $index): string
    {
        $year = date('Y');

        return 'PEL-'.$year.'-'.str_pad((string) $index, 8, '0', STR_PAD_LEFT);
    }
}
