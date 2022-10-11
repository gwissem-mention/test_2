<?php

declare(strict_types=1);

namespace App\FranceConnect;

use Symfony\Component\HttpFoundation\RequestStack;

class IdentitySessionHandler
{
    private const SESSION_NAME = 'france_connect_identity';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getIdentity(): Identity|null
    {
        $fcIdentity = $this->requestStack->getSession()->get(self::SESSION_NAME);
        if (!$fcIdentity instanceof Identity) {
            return null;
        }

        return $fcIdentity;
    }

    public function setIdentity(
        string $givenName,
        string $familyName,
        string $birthDate,
        string $gender,
        string $birthPlace,
        string $birthCountry,
        string $email
    ): void {
        $this->requestStack->getSession()->set(
            self::SESSION_NAME,
            new Identity(
                $givenName,
                $familyName,
                $birthDate,
                $gender,
                $birthPlace,
                $birthCountry,
                $email
            )
        );
    }

    public function removeIdentity(): void
    {
        $this->requestStack->getSession()->remove(self::SESSION_NAME);
    }

    public function isConnected(): bool
    {
        return $this->requestStack->getSession()->has(self::SESSION_NAME);
    }
}
