<?php

namespace App\Form\DTO;

use App\Entity\User;

class BulkAssignAction
{
    private ?User $assignedTo = null;
    private ?string $complaints = null;

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(User $assignedTo): void
    {
        $this->assignedTo = $assignedTo;
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
