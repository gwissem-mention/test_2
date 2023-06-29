<?php

declare(strict_types=1);

namespace App\Logger;

use App\AppEnum\Institution;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ApplicationTracesLogger
{
    public function __construct(
        private readonly LoggerInterface $applicationTracesGnLogger,
        private readonly LoggerInterface $applicationTracesPnLogger,
        private readonly Security $security
    ) {
    }

    public function log(string $message, ?User $user = null): void
    {
        $logger = $this->getLogger($user);

        $logger->info($message);
    }

    private function getLogger(?User $user): LoggerInterface
    {
        if (null === $user) {
            /** @var User $user */
            $user = $this->security->getUser();
        }

        return match ($user->getInstitution()) {
            Institution::GN => $this->applicationTracesGnLogger,
            Institution::PN => $this->applicationTracesPnLogger,
        };
    }
}
