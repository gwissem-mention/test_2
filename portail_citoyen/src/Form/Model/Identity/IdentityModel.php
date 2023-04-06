<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class IdentityModel
{
    private CivilStateModel $civilState;
    private ContactInformationModel $contactInformation;
    private ?CivilStateModel $representedPersonCivilState = null;
    private ?ContactInformationModel $representedPersonContactInformation = null;
    private ?CorporationModel $corporation = null;

    public function __construct()
    {
        $this->civilState = new CivilStateModel();
        $this->contactInformation = new ContactInformationModel();
    }

    public function getCivilState(): CivilStateModel
    {
        return $this->civilState;
    }

    public function setCivilState(CivilStateModel $civilState): self
    {
        $this->civilState = $civilState;

        return $this;
    }

    public function getContactInformation(): ContactInformationModel
    {
        return $this->contactInformation;
    }

    public function setContactInformation(ContactInformationModel $contactInformation): self
    {
        $this->contactInformation = $contactInformation;

        return $this;
    }

    public function getRepresentedPersonCivilState(): ?CivilStateModel
    {
        return $this->representedPersonCivilState;
    }

    public function setRepresentedPersonCivilState(?CivilStateModel $representedPersonCivilState): self
    {
        $this->representedPersonCivilState = $representedPersonCivilState;

        return $this;
    }

    public function getRepresentedPersonContactInformation(): ?ContactInformationModel
    {
        return $this->representedPersonContactInformation;
    }

    public function setRepresentedPersonContactInformation(?ContactInformationModel $representedPersonContactInformation
    ): self {
        $this->representedPersonContactInformation = $representedPersonContactInformation;

        return $this;
    }

    public function getCorporation(): ?CorporationModel
    {
        return $this->corporation;
    }

    public function setCorporation(?CorporationModel $corporation): self
    {
        $this->corporation = $corporation;

        return $this;
    }
}
