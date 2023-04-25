<?php

declare(strict_types=1);

namespace App\Form\Model;

class AppointmentModel
{
    private ?string $appointmentContactText = null;

    public function getAppointmentContactText(): ?string
    {
        return $this->appointmentContactText;
    }

    public function setAppointmentContactText(?string $appointmentContactText): self
    {
        $this->appointmentContactText = $appointmentContactText;

        return $this;
    }
}
