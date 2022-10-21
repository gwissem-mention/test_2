<?php

declare(strict_types=1);

namespace App\Generator;

use Symfony\Component\Uid\Uuid;

class UuidGenerator implements GeneratorInterface
{
    public function generate(): Uuid
    {
        return Uuid::v1();
    }
}
