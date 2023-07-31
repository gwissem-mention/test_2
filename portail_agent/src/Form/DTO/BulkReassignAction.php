<?php

declare(strict_types=1);

namespace App\Form\DTO;

class BulkReassignAction
{
    private ?string $unitCodeToReassign = null;
    private ?string $reassignText = null;
    private ?string $complaints = null;

    public function getUnitCodeToReassign(): ?string
    {
        return $this->unitCodeToReassign;
    }

    public function setUnitCodeToReassign(string $unitCodeToReassign): void
    {
        $this->unitCodeToReassign = $unitCodeToReassign;
    }

    public function getReassignText(): ?string
    {
        return $this->reassignText;
    }

    public function setReassignText(string $reassignText): void
    {
        $this->reassignText = $reassignText;
    }

    public function getComplaints(): ?string
    {
        return $this->complaints;
    }

    public function setComplaints(string $complaints): void
    {
        $this->complaints = $complaints;
    }
}
