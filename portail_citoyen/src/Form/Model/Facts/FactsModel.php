<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

class FactsModel
{
    private ?AddressModel $address = null;
    private ?OffenseNatureModel $offenseNature = null;
    private ?OffenseDateModel $offenseDate = null;
    private ?int $placeNature = null;
    private ?bool $victimOfViolence = null;
    private ?string $victimOfViolenceText = null;

    public function getAddress(): ?AddressModel
    {
        return $this->address;
    }

    public function setAddress(?AddressModel $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOffenseNature(): ?OffenseNatureModel
    {
        return $this->offenseNature;
    }

    public function setOffenseNature(?OffenseNatureModel $offenseNature): self
    {
        $this->offenseNature = $offenseNature;

        return $this;
    }

    public function getOffenseDate(): ?OffenseDateModel
    {
        return $this->offenseDate;
    }

    public function setOffenseDate(?OffenseDateModel $offenseDate): self
    {
        $this->offenseDate = $offenseDate;

        return $this;
    }

    public function getPlaceNature(): ?int
    {
        return $this->placeNature;
    }

    public function setPlaceNature(?int $placeNature): self
    {
        $this->placeNature = $placeNature;

        return $this;
    }

    public function isVictimOfViolence(): ?bool
    {
        return $this->victimOfViolence;
    }

    public function setVictimOfViolence(?bool $victimOfViolence): self
    {
        $this->victimOfViolence = $victimOfViolence;

        return $this;
    }

    public function getVictimOfViolenceText(): ?string
    {
        return $this->victimOfViolenceText;
    }

    public function setVictimOfViolenceText(?string $victimOfViolenceText): self
    {
        $this->victimOfViolenceText = $victimOfViolenceText;

        return $this;
    }
}
