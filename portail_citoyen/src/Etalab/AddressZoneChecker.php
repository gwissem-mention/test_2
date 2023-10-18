<?php

declare(strict_types=1);

namespace App\Etalab;

class AddressZoneChecker
{
    private const GIRONDE_DEPARTMENT_NUMBER = '33';

    public function isInsideGironde(string $departmentNumber): bool
    {
        return self::GIRONDE_DEPARTMENT_NUMBER === $departmentNumber;
    }
}
