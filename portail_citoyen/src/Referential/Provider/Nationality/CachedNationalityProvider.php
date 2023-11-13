<?php

declare(strict_types=1);

namespace App\Referential\Provider\Nationality;

use App\Referential\Entity\Nationality;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(decorates: NationalityProviderInterface::class)]
class CachedNationalityProvider implements NationalityProviderInterface
{
    private readonly NationalityProviderInterface $decorated;
    private readonly CacheInterface $referentialsCache;

    public function __construct(
        #[AutowireDecorated] NationalityProviderInterface $decorated,
        CacheInterface $referentialsCache,
    ) {
        $this->decorated = $decorated;
        $this->referentialsCache = $referentialsCache;
    }

    public function getChoices(): array
    {
        /** @var array<string, string> $cachedChoices */
        $cachedChoices = $this->referentialsCache->get('nationalities',
            fn (): array => $this->decorated->getChoices()
        );

        return $cachedChoices;
    }

    public function getByCode(string $code): Nationality
    {
        /** @var Nationality $nationality */
        $nationality = $this->referentialsCache->get('nationality_'.$code, fn () => $this->decorated->getByCode($code));

        return $nationality;
    }
}
