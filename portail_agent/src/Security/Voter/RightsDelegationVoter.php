<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/** @extends Voter<string, User> */
class RightsDelegationVoter extends Voter
{
    public const DELEGATION_RIGHTS = 'DELEGATION_RIGHTS';

    public function __construct(private readonly RoleHierarchyInterface $roleHierarchy)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::DELEGATION_RIGHTS === $attribute
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::DELEGATION_RIGHTS => $this->canAccessDelegationRights($user),
            default => false,
        };
    }

    private function canAccessDelegationRights(User $user): bool
    {
        $userRoles = $this->roleHierarchy->getReachableRoleNames($user->getRoles());

        return in_array('ROLE_SUPERVISOR', $userRoles, true) && !in_array('ROLE_DELEGATED', $userRoles, true);
    }
}
