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

    public function setId(?string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->housenumber;
    }

    public function setHouseNumber(?string $housenumber): static
    {
        $this->housenumber = $housenumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCitycode(): ?string
    {
        return $this->citycode;
    }

    public function setCitycode(?string $citycode): static
    {
        $this->citycode = $citycode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): static
    {
        $this->district = $district;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getX(): ?float
    {
        return $this->x;
    }

    public function setX(?float $x): static
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?float
    {
        return $this->y;
    }

    public function setY(?float $y): static
    {
        $this->y = $y;

        return $this;
    }

    public function getImportance(): ?float
    {
        return $this->importance;
    }

    public function setImportance(?float $importance): static
    {
        $this->importance = $importance;

        return $this;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
}
