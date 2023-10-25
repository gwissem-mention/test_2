<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\ComplaintAssignment;

class ComplaintAssignmentMessage
{
    public function __construct(
        private readonly int $complaintId,
        private readonly bool $isReassignment,
    ) {
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }

    public function isReassignment(): bool
    {
        return $this->isReassignment;
    }
}
