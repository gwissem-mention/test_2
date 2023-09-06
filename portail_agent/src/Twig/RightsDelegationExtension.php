<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User;
use App\Repository\RightDelegationRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RightsDelegationExtension extends AbstractExtension
{
    public function __construct(private readonly RightDelegationRepository $rightDelegationRepository)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_right_delegation_active', [$this, 'isRightDelegationActive']),
        ];
    }

    public function isRightDelegationActive(User $user): bool
    {
        $rightDelegation = $this->rightDelegationRepository->findOneByDelegatingAgent($user);

        return new \DateTimeImmutable() >= $rightDelegation?->getStartDate() && new \DateTimeImmutable() <= $rightDelegation?->getEndDate();
    }
}
