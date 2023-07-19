<?php

declare(strict_types=1);

namespace App\Form\Model\Identity;

class IdentityModel
{
    private ?int $declarantStatus = null;
    private ?bool $consentContactElectronics = null;
    private CivilStateModel $civilState;
    private ContactInformationModel $contactInformation;
    /* Person Legal Representative must be hidden for the experimentation */
    // private ?CivilStateModel $representedPersonCivilState = null;
    /* Person Legal Representative must be hidden for the experimentation */
    // private ?ContactInformationModel $representedPersonContactInformation = null;
    private ?CorporationModel $corporation = null;

    public function __construct()
    {
        $this->civilState = new CivilStateModel();
        $this->contactInformation = new ContactInformationModel();
    }

    public function getDeclarantStatus(): ?int
    {
        return $this->declarantStatus;
    }

    public function setDeclarantStatus(?int $declarantStatus): self
    {
        $this->declarantStatus = $declarantStatus;

        return $this;
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

    /* Person Legal Representative must be hidden for the experimentation */
    // public function getRepresentedPersonCivilState(): ?CivilStateModel
    // {
    //     return $this->representedPersonCivilState;
    // }

    /* Person Legal Representative must be hidden for the experimentation */
    // public function setRepresentedPersonCivilState(?CivilStateModel $representedPersonCivilState): self
    // {
    //     $this->representedPersonCivilState = $representedPersonCivilState;
    //
    //     return $this;
    // }

    /* Person Legal Representative must be hidden for the experimentation */
    // public function getRepresentedPersonContactInformation(): ?ContactInformationModel
    // {
    //     return $this->representedPersonContactInformation;
    // }

    /* Person Legal Representative must be hidden for the experimentation */
    // public function setRepresentedPersonContactInformation(?ContactInformationModel $representedPersonContactInformation
    // ): self {
    //     $this->representedPersonContactInformation = $representedPersonContactInformation;
    //
    //     return $this;
    // }

    public function getCorporation(): ?CorporationModel
    {
        return $this->corporation;
    }

    public function setCorporation(?CorporationModel $corporation): self
    {
        $this->corporation = $corporation;

        return $this;
    }

    public function getConsentContactElectronics(): ?bool
    {
        return $this->consentContactElectronics;
    }

    public function setConsentContactElectronics(?bool $consentContactElectronics): self
    {
        $this->consentContactElectronics = $consentContactElectronics;

        return $this;
    }
}
