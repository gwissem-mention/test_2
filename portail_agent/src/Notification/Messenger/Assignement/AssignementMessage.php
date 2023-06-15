<?php

declare(strict_types=1);

namespace App\Notification\Messenger\Assignement;

use App\Entity\Complaint;
use App\Entity\User;

class AssignementMessage
{
    public function __construct(
        private readonly Complaint $complaint,
        private readonly User $user,
        private readonly bool $isReassignment,
    ) {
    }

    public function getComplaint(): Complaint
    {
        return $this->complaint;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isReassignment(): bool
    {
        return $this->isReassignment;
    }
}
