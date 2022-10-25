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
    private ?string $description = null;
    private ?bool $amountKnown = null;
    private ?AdditionalInformationModel $additionalInformation = null;
    private ?int $amount = null;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getAdditionalInformation(): ?AdditionalInformationModel
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?AdditionalInformationModel $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

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

    /**
     * @return Collection<int, ObjectModel>
     */
    public function getObjects(): Collection
    {
        return $this->objects;
    }

    public function addObject(ObjectModel $object): void
    {
        $this->objects->add($object);
    }

    public function removeObject(ObjectModel $object): void
    {
        $this->objects->removeElement($object);
    }
}
