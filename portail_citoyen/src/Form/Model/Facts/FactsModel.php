<?php

declare(strict_types=1);

namespace App\Form\Model\Facts;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FactsModel
{
    private AddressModel $address;
    private OffenseNatureModel $offenseNature;
    private OffenseDateModel $offenseDate;
    private string $description;
    private bool $amountKnown;
    private AdditionalInformationModel $additionalInformation;
    private ?int $amount = null;
    /**
     * @var Collection<int, ObjectModel>
     */
    private Collection $objects;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
    }

    public function getAddress(): AddressModel
    {
        return $this->address;
    }

    public function setAddress(AddressModel $address): void
    {
        $this->address = $address;
    }

    public function getOffenseNature(): OffenseNatureModel
    {
        return $this->offenseNature;
    }

    public function setOffenseNature(OffenseNatureModel $offenseNature): void
    {
        $this->offenseNature = $offenseNature;
    }

    public function getOffenseDate(): OffenseDateModel
    {
        return $this->offenseDate;
    }

    public function setOffenseDate(OffenseDateModel $offenseDate): void
    {
        $this->offenseDate = $offenseDate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isAmountKnown(): bool
    {
        return $this->amountKnown;
    }

    public function setAmountKnown(bool $amountKnown): void
    {
        $this->amountKnown = $amountKnown;
    }

    public function getAdditionalInformation(): AdditionalInformationModel
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(AdditionalInformationModel $additionalInformation): void
    {
        $this->additionalInformation = $additionalInformation;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
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
