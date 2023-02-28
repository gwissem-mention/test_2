<?php

declare(strict_types=1);

namespace App\Generator\ComplaintNumber;

interface ComplaintNumberGeneratorInterface
{
    public function generate(int $index): mixed;
}
