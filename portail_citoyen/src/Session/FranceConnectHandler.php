<?php

namespace App\Session;

use App\Entity\User;
use App\Form\Factory\IdentityModelFactory;
use Symfony\Component\Security\Core\Security;

class FranceConnectHandler
{
    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly IdentityModelFactory $identityFactory,
        private readonly Security $security,
    ) {
    }

    public function setIdentityToComplaint(): void
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof User) {
            return;
        }

        /** @var ComplaintModel $complaint */
        $complaint = $this->sessionHandler->getComplaint();

        $this->sessionHandler->setComplaint(
            $complaint
                ->setFranceConnected(true)
                ->setIdentity(
                    $this->identityFactory->createFromFranceConnect(
                        $currentUser->getGivenName(),
                        $currentUser->getFamilyName(),
                        $currentUser->getBirthDate(),
                        $currentUser->getGender()->value,
                        $currentUser->getBirthPlace(),
                        $currentUser->getBirthCountry(),
                        $currentUser->getEmail(),
                        $currentUser->getPreferredUsername(),
                    )
                )
        );
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
