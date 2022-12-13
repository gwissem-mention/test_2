<?php

namespace App\Twig;

use App\Referential\Provider\Country\CachedCountryProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CountryReferentialExtension extends AbstractExtension
{
    public function __construct(protected readonly CachedCountryProvider $countryProvider)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('country_referential_name', [$this, 'getCountryName']),
        ];
    }

    public function getCountryName(string $inseeCode): string
    {
        return $this->countryProvider->getByInseeCode($inseeCode)->getLabel();
    }
}
