<?php

declare(strict_types=1);

namespace App\Form\Model\Objects;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
use Symfony\Component\Serializer\Annotation\Ignore;

class ObjectsModel
{
    /**
     * @var Collection<int, ObjectModel>
     */
    private Collection $objects;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
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

    /**
     * @return ReadableCollection<int, ObjectModel>
     */
    #[Ignore]
    public function getStolenObjects(): ReadableCollection
    {
        return $this->objects->filter(function (ObjectModel $object) {
            return ObjectModel::STATUS_STOLEN === $object->getStatus();
        });
    }

    /**
     * @return ReadableCollection<int, ObjectModel>
     */
    #[Ignore]
    public function getDegradedObjects(): ReadableCollection
    {
        return $this->objects->filter(function (ObjectModel $object) {
            return ObjectModel::STATUS_DEGRADED === $object->getStatus();
        });
    }
}
