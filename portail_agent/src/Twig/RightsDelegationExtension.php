<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\RightDelegation;
use App\Entity\User;
use App\Form\RightDelegationType;
use App\Repository\RightDelegationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class RightsDelegationExtension extends AbstractExtension implements GlobalsInterface
{
    private \DateTimeImmutable $today;

    public function __construct(
        private readonly RightDelegationRepository $rightDelegationRepository,
        private readonly Security $security,
        private readonly FormFactoryInterface $formFactory
    ) {
        $this->today = (new \DateTimeImmutable())->setTime(0, 0);
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
            'right_delegation_form' => $this->getRightDelegationFormView(),
        ];
    }

    public function isRightDelegationActive(User $user): bool
    {
        $rightDelegation = $this->rightDelegationRepository->findCurrentRightDelegationByDelegatingAgent($user);

        return $this->today >= $rightDelegation?->getStartDate() && $this->today <= $rightDelegation?->getEndDate();
    }

    public function hasRightDelegation(User $user): bool
    {
        $rightDelegation = $this->rightDelegationRepository->findCurrentRightDelegationByDelegatingAgent($user);

        return $rightDelegation && $this->today <= $rightDelegation->getEndDate();
    }

    private function getRightDelegation(): ?RightDelegation
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $this->rightDelegationRepository->findCurrentRightDelegationByDelegatingAgent($user);
    }

    private function getRightDelegationFormView(): ?FormView
    {
        /** @var ?User $user */
        $user = $this->security->getUser();
        $rightDelegation = $this->rightDelegationRepository->findCurrentRightDelegationByDelegatingAgent($user);

        return $user ? $this->formFactory->create(RightDelegationType::class, $rightDelegation)->createView() : null;
    }
}
