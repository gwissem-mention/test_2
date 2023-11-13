<?php

declare(strict_types=1);

namespace App\Referential\Provider\Country;

use App\Referential\Entity\Country;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(decorates: CountryProviderInterface::class)]
class CachedCountryProvider implements CountryProviderInterface
{
    private readonly CountryProviderInterface $decorated;
    private readonly CacheInterface $referentialsCache;

    public function __construct(
        #[AutowireDecorated] CountryProviderInterface $decorated,
        CacheInterface $referentialsCache,
    ) {
        $this->decorated = $decorated;
        $this->referentialsCache = $referentialsCache;
    }

    public function getChoices(): array
    {
        /** @var array<string, string> $cachedChoices */
        $cachedChoices = $this->referentialsCache->get('countries',
            fn (): array => $this->decorated->getChoices()
        );

        return $cachedChoices;
    }

    public function getByCode(string $code): Country
    {
        /** @var Country $cachedCountryByCode */
        $cachedCountryByCode = $this->referentialsCache->get('country_'.$code, fn () => $this->decorated->getByCode($code));

        return $cachedCountryByCode;
    }

    public function getByInseeCode(string $inseeCode): Country
    {
        /** @var Country $cachedCountryByInseeCode */
        $cachedCountryByInseeCode = $this->referentialsCache->get('country_'.$inseeCode, fn () => $this->decorated->getByInseeCode($inseeCode));

        return $cachedCountryByInseeCode;
    }
}
