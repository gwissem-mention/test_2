<?php

namespace App\Messenger\UnitReassignement;

use App\Entity\Complaint;

class AskUnitReassignementMessage
{
    public function __construct(
        private readonly Complaint $complaint,
        private readonly string $unitCode,
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
}
