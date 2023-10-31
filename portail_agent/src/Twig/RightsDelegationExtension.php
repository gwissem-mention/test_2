<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\RightDelegation;
use App\Entity\User;
use App\Repository\RightDelegationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class RightsDelegationExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly RightDelegationRepository $rightDelegationRepository,
        private readonly Security $security,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_right_delegation_active', [$this, 'isRightDelegationActive']),
            new TwigFunction('has_right_delegation', [$this, 'hasRightDelegation']),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'right_delegation' => $this->getRightDelegation(),
        ];
    }

    public function isRightDelegationActive(User $user): bool
    {
        $rightDelegation = $this->rightDelegationRepository->findOneByDelegatingAgent($user);

        return new \DateTimeImmutable() >= $rightDelegation?->getStartDate() && new \DateTimeImmutable() <= $rightDelegation?->getEndDate();
    }

    public function hasRightDelegation(User $user): bool
    {
        $rightDelegation = $this->rightDelegationRepository->findOneByDelegatingAgent($user);

        return $rightDelegation && new \DateTimeImmutable() <= $rightDelegation->getEndDate();
    }

    private function getRightDelegation(): ?RightDelegation
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $this->rightDelegationRepository->findOneByDelegatingAgent($user);
    }
}
