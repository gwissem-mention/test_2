<?php

declare(strict_types=1);

namespace App\Messenger\Appointment;

use App\Entity\Complaint;

class AppointmentMessage
{
    public function __construct(
        private readonly Complaint $complaint,
    ) {
    }

    public function getComplaint(): Complaint
    {
        return $this->complaint;
    }
}
