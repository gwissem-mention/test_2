<?php

namespace App\Referential\Provider\Country;

use App\Referential\Entity\Country;
use App\Referential\Provider\ProviderInterface;

interface CountryProviderInterface extends ProviderInterface
{
    public function getByCode(string $code): Country;

    public function getByInseeCode(string $inseeCode): Country;
}
