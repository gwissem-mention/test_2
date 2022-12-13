<?php

namespace App\Referential\Provider\Country;

use App\Referential\Entity\Country;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\MapDecorated;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(decorates: CountryProviderInterface::class)]
class CachedCountryProvider implements CountryProviderInterface
{
    private readonly CountryProviderInterface $decorated;
    private readonly CacheInterface $referentialsCache;

    public function __construct(
        #[MapDecorated] CountryProviderInterface $decorated,
        CacheInterface $referentialsCache,
    ) {
        $this->decorated = $decorated;
        $this->referentialsCache = $referentialsCache;
    }

    public function getChoices(): array
    {
        /** @var array<string, string> $cachedChoices */
        $cachedChoices = $this->referentialsCache->get('countries',
            function (): array {
                return $this->decorated->getChoices();
            }
        );

        return $cachedChoices;
    }

    public function getByCode(string $code): Country
    {
        /** @var Country $cachedCountryByCode */
        $cachedCountryByCode = $this->referentialsCache->get('country_'.$code, function () use ($code) {
            return $this->decorated->getByCode($code);
        });

        return $cachedCountryByCode;
    }

    public function getByInseeCode(string $inseeCode): Country
    {
        /** @var Country $cachedCountryByInseeCode */
        $cachedCountryByInseeCode = $this->referentialsCache->get('country_'.$inseeCode, function () use ($inseeCode) {
            return $this->decorated->getByInseeCode($inseeCode);
        });

        return $cachedCountryByInseeCode;
    }
}
