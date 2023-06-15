<?php

declare(strict_types=1);

namespace App\Notification\Messenger\UnitReassignement;

use App\Entity\Complaint;
use App\Entity\User;

class UnitReassignementMessage
{
    public function __construct(
        private readonly Complaint $complaint,
        private readonly string $unitCode,
        private readonly bool $reassignmentAsked,
        private readonly ?User $reassignmentAskBy = null
    ) {
    }

    public function getComplaint(): Complaint
    {
        return $this->complaint;
    }

    public function getUnitCode(): string
    {
        return $this->unitCode;
    }

    public function isReassignmentAsked(): bool
    {
        return $this->reassignmentAsked;
    }

    public function getReassignmentAskBy(): ?User
    {
        return $this->reassignmentAskBy;
    }
}
