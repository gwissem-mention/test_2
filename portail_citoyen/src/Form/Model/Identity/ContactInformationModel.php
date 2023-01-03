<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class ContactInformationModel implements EmbedAddressInterface
{
    use AddressTrait;
    use EmailTrait;

    private ?PhoneModel $phone = null;

    public function getPhone(): ?PhoneModel
    {
        return $this->phone;
    }

    public function setPhone(?PhoneModel $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
