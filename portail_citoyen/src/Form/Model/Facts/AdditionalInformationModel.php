<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class AdditionalInformationModel
{
    private bool $suspectsChoice;
    private bool $witnesses;
    private bool $fsiVisit;
    private int $cctvPresent;
    private ?string $suspectsText = null;
    private ?string $witnessesText = null;
    private ?bool $observationMade = null;
    private ?bool $cctvAvailable = null;

    public function isSuspectsChoice(): bool
    {
        return $this->suspectsChoice;
    }

    public function setSuspectsChoice(bool $suspectsChoice): void
    {
        $this->suspectsChoice = $suspectsChoice;
    }

    public function isWitnesses(): bool
    {
        return $this->witnesses;
    }

    public function setWitnesses(bool $witnesses): void
    {
        $this->witnesses = $witnesses;
    }

    public function isFsiVisit(): bool
    {
        return $this->fsiVisit;
    }

    public function setFsiVisit(bool $fsiVisit): void
    {
        $this->fsiVisit = $fsiVisit;
    }

    public function getCctvPresent(): int
    {
        return $this->cctvPresent;
    }

    public function setCctvPresent(int $cctvPresent): void
    {
        $this->cctvPresent = $cctvPresent;
    }

    public function getSuspectsText(): ?string
    {
        return $this->suspectsText;
    }

    public function setSuspectsText(?string $suspectsText): void
    {
        $this->suspectsText = $suspectsText;
    }

    public function getWitnessesText(): ?string
    {
        return $this->witnessesText;
    }

    public function setWitnessesText(?string $witnessesText): void
    {
        $this->witnessesText = $witnessesText;
    }

    public function isObservationMade(): ?bool
    {
        return $this->observationMade;
    }

    public function setObservationMade(?bool $observationMade): void
    {
        $this->observationMade = $observationMade;
    }

    public function isCctvAvailable(): ?bool
    {
        return $this->cctvAvailable;
    }

    public function setCctvAvailable(?bool $cctvAvailable): void
    {
        $this->cctvAvailable = $cctvAvailable;
    }
}
