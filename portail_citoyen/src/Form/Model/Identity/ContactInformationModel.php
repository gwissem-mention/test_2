<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

use App\Form\Model\EtalabInput;

class ContactInformationModel implements EmbedAddressInterface
{
    use AddressTrait;
    use EmailTrait;

    private ?PhoneModel $mobile = null;
    private ?PhoneModel $phone = null;
    public EtalabInput $etalabInput;

    public function __construct()
    {
        $this->etalabInput = new EtalabInput();
    }

    public function getPhone(): ?PhoneModel
    {
        return $this->phone;
    }

    public function setPhone(?PhoneModel $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobile(): ?PhoneModel
    {
        return $this->mobile;
    }

    public function setMobile(?PhoneModel $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }
}
