<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Complaint;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/** @extends Voter<string, Complaint> */
class ComplaintVoter extends Voter
{
    public const VIEW = 'COMPLAINT_VIEW';

    public function __construct(private readonly RoleHierarchyInterface $roleHierarchy)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::VIEW === $attribute
            && $subject instanceof Complaint;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var Complaint $complaint */
        $complaint = $subject;
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($complaint, $user),
            default => false,
        };
    }

    private function canView(Complaint $complaint, User $user): bool
    {
        $userRoles = $this->roleHierarchy->getReachableRoleNames($user->getRoles());

        return (in_array('ROLE_SUPERVISOR', $userRoles) && $complaint->getUnitAssigned() === $user->getServiceCode())
            || ($complaint->getAssignedTo() === $user);
    }
}
