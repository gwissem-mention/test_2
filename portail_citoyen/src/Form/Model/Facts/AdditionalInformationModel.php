<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class AdditionalInformationModel
{
    private ?bool $suspectsChoice = null;
    private ?bool $witnesses = null;
    private ?bool $fsiVisit = null;
    private ?int $cctvPresent = null;
    private ?string $suspectsText = null;
    private ?string $witnessesText = null;
    private ?bool $observationMade = null;
    private ?bool $cctvAvailable = null;

    public function isSuspectsChoice(): ?bool
    {
        return $this->suspectsChoice;
    }

    public function setSuspectsChoice(?bool $suspectsChoice): self
    {
        $this->suspectsChoice = $suspectsChoice;

        return $this;
    }

    public function isWitnesses(): ?bool
    {
        return $this->witnesses;
    }

    public function setWitnesses(?bool $witnesses): self
    {
        $this->witnesses = $witnesses;

        return $this;
    }

    public function isFsiVisit(): ?bool
    {
        return $this->fsiVisit;
    }

    public function setFsiVisit(?bool $fsiVisit): self
    {
        $this->fsiVisit = $fsiVisit;

        return $this;
    }

    public function getCctvPresent(): ?int
    {
        return $this->cctvPresent;
    }

    public function setCctvPresent(?int $cctvPresent): self
    {
        $this->cctvPresent = $cctvPresent;

        return $this;
    }

    public function getSuspectsText(): ?string
    {
        return $this->suspectsText;
    }

    public function setSuspectsText(?string $suspectsText): self
    {
        $this->suspectsText = $suspectsText;

        return $this;
    }

    public function getWitnessesText(): ?string
    {
        return $this->witnessesText;
    }

    public function setWitnessesText(?string $witnessesText): self
    {
        $this->witnessesText = $witnessesText;

        return $this;
    }

    public function isObservationMade(): ?bool
    {
        return $this->observationMade;
    }

    public function setObservationMade(?bool $observationMade): self
    {
        $this->observationMade = $observationMade;

        return $this;
    }

    public function isCctvAvailable(): ?bool
    {
        return $this->cctvAvailable;
    }

    public function setCctvAvailable(?bool $cctvAvailable): self
    {
        $this->cctvAvailable = $cctvAvailable;

        return $this;
    }
}
