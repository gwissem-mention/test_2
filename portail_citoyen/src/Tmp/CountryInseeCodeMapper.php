<?php

namespace App\Tmp;

class CountryInseeCodeMapper
{
    /** @var array|string[] */
    private array $map = [
        '99100' => 'FR',
        '99352' => 'DZ',
        '99350' => 'MA',
        '99139' => 'PT',
        '99131' => 'BE',
        '99137' => 'LU',
        '99134' => 'ES',
        '99127' => 'IT',
        '99397' => 'KM',
        '99123' => 'EE',
        '99217' => 'JP',
        '99121' => 'BA',
        '99243' => 'VN',
    ];

    public function isSupportedInseeCode(?string $inseeCode): bool
    {
        return isset($this->map[$inseeCode]);
    }

    public function getCountryCode(?string $inseeCode): string
    {
        return $this->map[$inseeCode] ?? 'FR';
    }
}
