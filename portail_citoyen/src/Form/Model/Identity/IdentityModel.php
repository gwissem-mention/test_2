<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class IdentityModel
{
    private int $declarantStatus;
    private ?CivilStateModel $civilState = null;
    private ?ContactInformationModel $contactInformation = null;
    private ?CivilStateModel $representedPersonCivilState = null;
    private ?ContactInformationModel $representedPersonContactInformation = null;
    private ?CorporationModel $corporation = null;

    public function getDeclarantStatus(): int
    {
        return $this->declarantStatus;
    }

    public function setDeclarantStatus(int $declarantStatus): void
    {
        $this->declarantStatus = $declarantStatus;
    }

    public function getCivilState(): ?CivilStateModel
    {
        return $this->civilState;
    }

    public function setCivilState(?CivilStateModel $civilState): void
    {
        $this->civilState = $civilState;
    }

    public function getContactInformation(): ?ContactInformationModel
    {
        return $this->contactInformation;
    }

    public function setContactInformation(?ContactInformationModel $contactInformation): void
    {
        $this->contactInformation = $contactInformation;
    }

    public function getRepresentedPersonCivilState(): ?CivilStateModel
    {
        return $this->representedPersonCivilState;
    }

    public function setRepresentedPersonCivilState(?CivilStateModel $representedPersonCivilState): void
    {
        $this->representedPersonCivilState = $representedPersonCivilState;
    }

    public function getRepresentedPersonContactInformation(): ?ContactInformationModel
    {
        return $this->representedPersonContactInformation;
    }

    public function setRepresentedPersonContactInformation(?ContactInformationModel $representedPersonContactInformation): void
    {
        $this->representedPersonContactInformation = $representedPersonContactInformation;
    }

    public function getCorporation(): ?CorporationModel
    {
        return $this->corporation;
    }

    public function setCorporation(?CorporationModel $corporation): void
    {
        $this->corporation = $corporation;
    }
}
