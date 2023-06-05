<?php

declare(strict_types=1);

namespace App\Referential\Provider\Nationality;

use App\Referential\Entity\Nationality;
use App\Referential\Provider\ProviderInterface;

interface NationalityProviderInterface extends ProviderInterface
{
    public function getByCode(string $code): Nationality;
}
