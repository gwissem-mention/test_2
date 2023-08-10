<?php

declare(strict_types=1);

namespace App\Form\Model;

class AppointmentModel
{
    private ?string $appointmentContactText = null;
    private ?bool $appointmentAsked = null;

    public function getAppointmentContactText(): ?string
    {
        return $this->appointmentContactText;
    }

    public function setAppointmentContactText(?string $appointmentContactText): self
    {
        $this->appointmentContactText = $appointmentContactText;

        return $this;
    }

    public function isAppointmentAsked(): ?bool
    {
        return $this->appointmentAsked;
    }

    public function setAppointmentAsked(?bool $appointmentAsked): self
    {
        $this->appointmentAsked = $appointmentAsked;

        return $this;
    }
}
