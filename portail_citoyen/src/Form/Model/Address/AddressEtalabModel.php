<?php

declare(strict_types=1);

namespace App\Form\Model\Address;

class AddressEtalabModel extends AbstractSerializableAddress
{
    private ?string $id = null;
    private ?string $label = null;
    private ?string $type = null;
    private ?float $score = null;
    private ?string $housenumber = null;
    private ?string $street = null;
    private ?string $name = null;
    private ?string $postcode = null;
    private ?string $citycode = null;
    private ?string $city = null;
    private ?string $district = null;
    private ?string $context = null;
    private ?float $x = null;
    private ?float $y = null;
    private ?float $importance = null;

    public function __construct()
    {
        $this->addressType = 'etalab_address';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->housenumber;
    }

    public function setHouseNumber(?string $housenumber): self
    {
        $this->housenumber = $housenumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCitycode(): ?string
    {
        return $this->citycode;
    }

    public function setCitycode(?string $citycode): self
    {
        $this->citycode = $citycode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function setX(?float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function setY(?float $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getImportance(): ?float
    {
        return $this->importance;
    }

    public function setImportance(?float $importance): self
    {
        $this->importance = $importance;

        return $this;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
}
