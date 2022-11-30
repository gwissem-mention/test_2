<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FactsModel
{
    private ?AddressModel $address = null;
    private ?OffenseNatureModel $offenseNature = null;
    private ?OffenseDateModel $offenseDate = null;
    private ?bool $amountKnown = null;
    private ?int $amount = null;
    private ?int $placeNature = null;
    private ?bool $victimOfViolence = null;
    private ?string $victimOfViolenceText = null;

    /**
     * @var Collection<int, ObjectModel>
     */
    private Collection $objects;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
    }

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

    public function isAmountKnown(): ?bool
    {
        return $this->amountKnown;
    }

    public function setAmountKnown(?bool $amountKnown): self
    {
        $this->amountKnown = $amountKnown;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

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

    /**
     * @return Collection<int, ObjectModel>
     */
    public function getObjects(): Collection
    {
        return $this->objects;
    }

    public function addObject(ObjectModel $object): self
    {
        $this->objects->add($object);

        return $this;
    }

    public function removeObject(ObjectModel $object): self
    {
        $this->objects->removeElement($object);

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
