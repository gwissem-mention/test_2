<?php

declare(strict_types=1);

namespace App\Form\Model\Objects;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ObjectsModel
{
    private ?bool $amountKnown = null;
    private ?int $amount = null;

    /**
     * @var Collection<int, ObjectModel>
     */
    private Collection $objects;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
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
}
