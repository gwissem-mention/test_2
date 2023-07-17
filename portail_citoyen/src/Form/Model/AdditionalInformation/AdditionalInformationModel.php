<?php

declare(strict_types=1);

namespace App\Form\Model\AdditionalInformation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class AdditionalInformationModel
{
    public const CCTV_PRESENT_YES = 1;
    public const CCTV_PRESENT_NO = 2;
    public const CCTV_PRESENT_DONT_KNOW = 3;

    private ?bool $suspectsChoice = null;
    private ?bool $witnessesPresent = null;
    private ?bool $fsiVisit = null;
    private ?int $cctvPresent = null;
    private ?string $suspectsText = null;
    /** @var Collection<int, WitnessModel> */
    private Collection $witnesses;
    private ?bool $observationMade = null;
    private ?bool $cctvAvailable = null;

    public function __construct()
    {
        $this->witnesses = new ArrayCollection();
    }

    public function isSuspectsChoice(): ?bool
    {
        return $this->suspectsChoice;
    }

    public function setSuspectsChoice(?bool $suspectsChoice): self
    {
        $this->suspectsChoice = $suspectsChoice;

        return $this;
    }

    public function isWitnessesPresent(): ?bool
    {
        return $this->witnessesPresent;
    }

    public function setWitnessesPresent(?bool $witnessesPresent): self
    {
        $this->witnessesPresent = $witnessesPresent;

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

    /**
     * @return array<string, int>
     */
    public static function getCctvPresentChoices(): array
    {
        return [
            'pel.yes' => self::CCTV_PRESENT_YES,
            'pel.no' => self::CCTV_PRESENT_NO,
            'pel.i.dont.know' => self::CCTV_PRESENT_DONT_KNOW,
        ];
    }

    /**
     * @return Collection<int, WitnessModel>
     */
    public function getWitnesses(): Collection
    {
        return $this->witnesses;
    }

    public function addWitness(WitnessModel $witness): self
    {
        $this->witnesses->add($witness);

        return $this;
    }

    public function removeWitness(WitnessModel $witness): self
    {
        $this->witnesses->removeElement($witness);

        return $this;
    }
}
