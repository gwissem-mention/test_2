<?php

namespace App\Session;

use App\Form\Factory\IdentityModelFactory;

class FranceConnectHandler
{
    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly IdentityModelFactory $identityFactory
    ) {
    }

    public function set(
        string $givenName,
        string $familyName,
        string $birthDate,
        string $gender,
        string $birthPlace,
        string $birthCountry,
        string $email,
        ?string $usageName = null
    ): void {
        $complaint = $this->sessionHandler->getComplaint();

        if (null === $complaint) {
            return;
        }

        $complaint
            ->setFranceConnected(true)
            ->setIdentity(
                $this->identityFactory->createFromFranceConnect(
                    $givenName,
                    $familyName,
                    $birthDate,
                    $gender,
                    $birthPlace,
                    $birthCountry,
                    $email,
                    $usageName
                )
            );

        $this->sessionHandler->setComplaint($complaint);
    }

    public function clear(): void
    {
        $complaint = $this->sessionHandler->getComplaint();

        if (null === $complaint) {
            return;
        }

        $complaint
            ->setFranceConnected(false)
            ->setIdentity(null);

        $this->sessionHandler->setComplaint($complaint);
    }

    public function isFranceConnected(): bool
    {
        $complaint = $this->sessionHandler->getComplaint();

        if (null === $complaint) {
            return false;
        }

        return $complaint->isFranceConnected();
    }
}
