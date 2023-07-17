<?php

declare(strict_types=1);

namespace App\Form\Model\AdditionalInformation;

use App\Form\Model\Identity\PhoneModel;

class WitnessModel
{
    private ?string $description = null;
    private ?string $email = null;
    private ?PhoneModel $phone = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?PhoneModel
    {
        return $this->phone;
    }

    public function setPhone(?PhoneModel $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
