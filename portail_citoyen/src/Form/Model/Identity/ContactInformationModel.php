<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class ContactInformationModel implements EmbedAddressInterface
{
    use AddressTrait;
    use EmailTrait;

    private ?string $mobile = null;

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }
}
