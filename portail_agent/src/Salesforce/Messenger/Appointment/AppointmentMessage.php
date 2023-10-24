<?php

declare(strict_types=1);

namespace App\Salesforce\Messenger\Appointment;

class AppointmentMessage
{
    public function __construct(
        private readonly int $complaintId,
        private readonly bool $update
    ) {
    }

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }

    public function isUpdate(): bool
    {
        return $this->update;
    }
}
